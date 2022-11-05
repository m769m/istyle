<?php

namespace App\Controllers;

use App\System\Core\Controller;
use Themes\Purple\Models\CatalogServiceModel;
use Themes\Purple\Models\FaqQuestionModel;
use Themes\Purple\Models\PageModel;
use Themes\Purple\Models\PurpleModel;
use Themes\Purple\Models\SalonCardModel;

use function App\app;
use function App\clear_input;
use function App\db;
use function App\get_text;
use function App\getPagination;
use function App\search_input;

class Catalog extends Controller
{

    function __construct()
    {
        $this->loadTheme('purple');
        $this->currency = app()->currency['currency_symbol'];
    }

    function main()
    {
        $variables = [
            'is_service_catalog' => false
        ];
        $variables['sellers'] = [];

        foreach (app()->categories as $category) {
            $item['title'] = get_text($category['lang_key'], $category['service_category_name']);
            $item['url'] = '/catalog/' . $category['service_category_slug'];
            $variables['categories'][] = $item;
        }
     
        $query = [
            "status" => "user_status = 'active'",
            "role" => "(user_role = 'salon' OR user_role = 'master')"
        ];
        $orderBy = "`recommended` DESC";
        if(intval($_GET['filter']) === 1) {
            $city = search_input($_GET['city']);
            $street = search_input($_GET['street']);
            $rating = intval($_GET['rating']);
            $sort = $_GET['sort'];
            $view = $_GET['view'];

            if(!empty($sort) and !in_array($sort, ['recommended', 'user_rating']) or !empty($view) and !in_array($view, ['master', 'salon']) or $rating > 0 and $rating > 5) {
                exit('data error');
            }

            if(!empty($view)) {
                $query['role'] = "user_role = '$view'";
            }

            if(!empty($sort)) {
                $orderBy = "`$sort` DESC";
            }

            if($rating > 0) {
                $maxRating = $rating+0.5;
                $query['rating'] = "(user_rating >= $rating AND user_rating <= $maxRating)";
            }

            if(!empty($city)) {
                $query['adress'] = "contact_adress LIKE '%$city%'";
                if(!empty($street)) {
                    $query['adress'] = '('.$query['adress']." AND contact_adress LIKE '%$street%')";
                }
            }
        }

        $queryStr = implode(' AND ', $query);

        $itemsCount = db()->find_one("SELECT COUNT(*) FROM user WHERE $queryStr");
        $itemsCount = $itemsCount['COUNT(*)'];

        $variables['pagination'] = $this->_getPagination($itemsCount);
        $limit = $variables['pagination']['limit'];
        $offset = $variables['pagination']['offset'];

        if ($offset > $itemsCount) {
            return false;
        }

        $sellers = db()->select("SELECT * FROM `user` WHERE $queryStr ORDER BY $orderBy LIMIT $limit OFFSET $offset");

        foreach ($sellers as $seller) {
            $seller = new SalonCardModel($seller);
            if (!empty($seller->variables['services'])) {
                $variables['sellers'][] = $seller;
            }
        }

        $variables['currency'] = $this->currency;

        $this->model = new PageModel(get_text('service_catalog'), new PurpleModel('content/catalog', $variables), null, null, '', 'default-page-wrapper', '', [get_text('all_services')]);
        $this->model->load();
    }

    function category($category_slug)
    {
        $variables = [
            'is_service_catalog' => true
        ];

        $currentCategory = false;
        foreach (app()->categories as $category) {
            if ($category_slug === $category['service_category_slug']) {
                $currentCategory = $category;
                break;
            }
        }
        if ($currentCategory === false) {
            return false;
        }

        $categoryTitle = get_text($currentCategory['lang_key'], $currentCategory['service_category_name']);
        $categorySlug = $currentCategory['service_category_slug'];


        $catId = $currentCategory['service_category_id'];

        $subCategories = db()->find('service_subcategory', ['service_category_id' => $catId, 'service_subcategory_status' => 'active']);

        foreach ($subCategories as $category) {
            $item['title'] = get_text($category['lang_key'], $category['service_subcategory_name']);
            $item['url'] = "/catalog/$categorySlug/" . $category['service_subcategory_slug'];
            $variables['categories'][] = $item;
        }

        $query = [
            "status" => "user.user_status = 'active'",
            "service_status" => "user_service.user_service_status = 'active'",
            "category" => "service_subcategory.service_category_id = $catId"
        ];
        $this->_getFiltersQuery($query, "user.`recommended` DESC");
        $queryStr = $this->query_string;
        $orderBy = $this->order_by;

        $itemsCount = db()->find_one(
            "SELECT COUNT(*) FROM `user_service`
            LEFT JOIN `service`
            ON `user_service`.service_id = `service`.service_id
            LEFT JOIN `service_subcategory`
            ON `service`.service_subcategory_id = `service_subcategory`.service_subcategory_id
            LEFT JOIN `service_category`
            ON `service_subcategory`.service_category_id = `service_category`.service_category_id
            RIGHT JOIN `user`
            ON user_service.user_id = `user`.`user_id`
            WHERE $queryStr
            ORDER BY $orderBy"
        );
        $itemsCount = $itemsCount['COUNT(*)'];

        $variables['pagination'] = $this->_getPagination($itemsCount);
        $limit = $variables['pagination']['limit'];
        $offset = $variables['pagination']['offset'];

        if ($offset > $itemsCount) {
            return false;
        }

        $services = db()->select(
            "SELECT
                `user`.`user_id`,
                `user`.`user_role`,
                `user`.`first_name`,
                `user`.`last_name`,
                `user`.`salon_name`,
                `user`.`contact_adress`,
                `user_service`.`user_service_id`,
                `user_service`.`user_service_name`,
                `user_service`.`user_service_rating`,
                `user_service`.`user_service_price`,
                `user_service`.`user_service_discount`,
                `user_service`.`user_service_discount_expire`,
                `user_service`.`user_service_time`,
                `user_service`.`time_unit`,
                `service`.`service_name`,
                `service`.`service_slug`,
                `service`.`lang_key`,
                `service_subcategory`.`service_subcategory_slug`,
                `service_subcategory`.`service_subcategory_name`,
                `service_subcategory`.`lang_key` AS subcategory_lang_key,
                `service_category`.`service_category_slug`
            FROM `user_service`
            LEFT JOIN `service`
            ON `user_service`.service_id = `service`.service_id
            LEFT JOIN `service_subcategory`
            ON `service`.service_subcategory_id = `service_subcategory`.service_subcategory_id
            LEFT JOIN `service_category`
            ON `service_subcategory`.service_category_id = `service_category`.service_category_id
            RIGHT JOIN `user`
            ON user_service.user_id = `user`.`user_id`
            WHERE $queryStr
            ORDER BY $orderBy
            LIMIT $limit
            OFFSET $offset"
        );

        $variables['sellers'] = [];
        foreach ($services as $service) {
            $variables['sellers'][] = new CatalogServiceModel($service, true);
        }

        $variables['currency'] = $this->currency;

        $breadcrumbs = [
            [
                'title' => get_text('home'),
                'link' => '/',
                'active' => false
            ],
            [
                'title' => get_text('service_catalog'),
                'link' => '/catalog',
                'active' => false
            ],
            [
                'title' => $categoryTitle,
                'link' => '/catalog/' . $categorySlug,
                'active' => true
            ]
        ];

        $titleBread = [get_text('all_services'), $categoryTitle];

        $this->model = new PageModel($categoryTitle . ' / ' . get_text('service_catalog'), new PurpleModel('content/catalog', $variables), get_text('service_catalog'), $breadcrumbs, '', 'default-page-wrapper', '/catalog', $titleBread);
        $this->model->load();
    }

    function subcategory($category_slug, $subcategory_slug)
    {
        $variables = [
            'is_service_catalog' => true
        ];

        $currentCategory = false;
        foreach (app()->categories as $category) {
            if ($category_slug === $category['service_category_slug']) {
                $currentCategory = $category;
                break;
            }
        }
        if ($currentCategory === false) {
            return false;
        }

        $categoryTitle = get_text($currentCategory['lang_key'], $currentCategory['service_category_name']);
        $categorySlug = $currentCategory['service_category_slug'];
        $catId = $currentCategory['service_category_id'];

        $subCategories = db()->find('service_subcategory', ['service_category_id' => $catId, 'service_subcategory_status' => 'active']);

        $currentSubCategory = false;
        foreach ($subCategories as $category) {
            if ($subcategory_slug === $category['service_subcategory_slug']) {
                $currentSubCategory = $category;
                break;
            }
        }

        if ($currentSubCategory === false) {
            return false;
        }

        $subcategoryTitle = get_text($currentSubCategory['lang_key'], $currentSubCategory['service_subcategory_name']);
        $subcategorySlug = $currentSubCategory['service_subcategory_slug'];
        $subcatId = $currentSubCategory['service_subcategory_id'];

        $tags = db()->find('service', ['service_subcategory_id' => $subcatId, 'service_status' => 'active']);

        foreach ($tags as $tag) {
            $item['title'] = get_text($tag['lang_key'], $tag['service_name']);
            $item['url'] = "/catalog/$categorySlug/$subcategorySlug/" . $tag['service_slug'];
            $variables['categories'][] = $item;
        }

        
        $query = [
            "status" => "user.user_status = 'active'",
            "service_status" => "user_service.user_service_status = 'active'",
            "category" => "service.service_subcategory_id = $subcatId"
        ];
        $this->_getFiltersQuery($query, "user.`recommended` DESC");
        $queryStr = $this->query_string;
        $orderBy = $this->order_by;

        $itemsCount = db()->find_one(
            "SELECT COUNT(*) FROM `user_service`
            LEFT JOIN `service`
            ON `user_service`.service_id = `service`.service_id
            LEFT JOIN `service_subcategory`
            ON `service`.service_subcategory_id = `service_subcategory`.service_subcategory_id
            LEFT JOIN `service_category`
            ON `service_subcategory`.service_category_id = `service_category`.service_category_id
            RIGHT JOIN `user`
            ON user_service.user_id = `user`.`user_id`
            WHERE $queryStr"
        );
        $itemsCount = $itemsCount['COUNT(*)'];

        $variables['pagination'] = $this->_getPagination($itemsCount);
        $limit = $variables['pagination']['limit'];
        $offset = $variables['pagination']['offset'];

        if ($offset > $itemsCount) {
            return false;
        }

        $services = db()->select(
            "SELECT
                `user`.`user_id`,
                `user`.`user_role`,
                `user`.`first_name`,
                `user`.`last_name`,
                `user`.`salon_name`,
                `user`.`contact_adress`,
                `user_service`.`user_service_id`,
                `user_service`.`user_service_name`,
                `user_service`.`user_service_rating`,
                `user_service`.`user_service_price`,
                `user_service`.`user_service_discount`,
                `user_service`.`user_service_discount_expire`,
                `user_service`.`user_service_time`,
                `user_service`.`time_unit`,
                `service`.`service_name`,
                `service`.`service_slug`,
                `service`.`lang_key`,
                `service_subcategory`.`service_subcategory_slug`,
                `service_subcategory`.`service_subcategory_name`,
                `service_subcategory`.`lang_key` AS subcategory_lang_key,
                `service_category`.`service_category_slug`
            FROM `user_service`
            LEFT JOIN `service`
            ON `user_service`.service_id = `service`.service_id
            LEFT JOIN `service_subcategory`
            ON `service`.service_subcategory_id = `service_subcategory`.service_subcategory_id
            LEFT JOIN `service_category`
            ON `service_subcategory`.service_category_id = `service_category`.service_category_id
            RIGHT JOIN `user`
            ON user_service.user_id = `user`.`user_id`
            WHERE $queryStr
            ORDER BY $orderBy
            LIMIT $limit
            OFFSET $offset"
        );

        $variables['sellers'] = [];
        foreach ($services as $service) {
            $variables['sellers'][] = new CatalogServiceModel($service, false);
        }

        $variables['currency'] = $this->currency;

        $breadcrumbs = [
            [
                'title' => get_text('home'),
                'link' => '/',
                'active' => false
            ],
            [
                'title' => get_text('service_catalog'),
                'link' => '/catalog',
                'active' => false
            ],
            [
                'title' => $categoryTitle,
                'link' => "/catalog/$categorySlug",
                'active' => false
            ],
            [
                'title' => $subcategoryTitle,
                'link' => "/catalog/$categorySlug/$subcategorySlug",
                'active' => true
            ]
        ];

        $titleBread = [get_text('all_services'), $categoryTitle, $subcategoryTitle];

        $this->model = new PageModel($subcategoryTitle . ' / ' . $categoryTitle . ' / ' . get_text('service_catalog'), new PurpleModel('content/catalog', $variables), get_text('service_catalog'), $breadcrumbs, '', 'default-page-wrapper', "/catalog/$categorySlug", $titleBread);
        $this->model->load();
    }

    function tag($category_slug, $subcategory_slug, $tag_slug)
    {
        $variables = [
            'is_service_catalog' => true
        ];

        $currentCategory = false;
        foreach (app()->categories as $category) {
            if ($category_slug === $category['service_category_slug']) {
                $currentCategory = $category;
                break;
            }
        }
        if ($currentCategory === false) {
            return false;
        }

        $categoryTitle = get_text($currentCategory['lang_key'], $currentCategory['service_category_name']);
        $categorySlug = $currentCategory['service_category_slug'];
        $catId = $currentCategory['service_category_id'];

        $subCategories = db()->find('service_subcategory', ['service_category_id' => $catId, 'service_subcategory_status' => 'active']);

        $currentSubCategory = false;
        foreach ($subCategories as $category) {
            if ($subcategory_slug === $category['service_subcategory_slug']) {
                $currentSubCategory = $category;
                break;
            }
        }
        if ($currentSubCategory === false) {
            return false;
        }

        $subcategoryTitle = get_text($currentSubCategory['lang_key'], $currentSubCategory['service_subcategory_name']);
        $subcategorySlug = $currentSubCategory['service_subcategory_slug'];
        $subcatId = $currentSubCategory['service_subcategory_id'];

        $tags = db()->find('service', ['service_subcategory_id' => $subcatId, 'service_status' => 'active']);

        $currentTag = false;
        foreach ($tags as $tag) {
            if ($tag['service_slug'] === $tag_slug) {
                $currentTag = $tag;
                break;
            }
        }

        if ($currentTag === false) {
            return false;
        }

        $tagTitle = get_text($currentTag['lang_key'], $currentTag['service_name']);
        $tagSlug = $currentTag['service_slug'];
        $tagId = $currentTag['service_id'];

        $query = [
            "status" => "user.user_status = 'active'",
            "service_status" => "user_service.user_service_status = 'active'",
            "category" => "service.service_id = $tagId"
        ];
        $this->_getFiltersQuery($query, "user.`recommended` DESC");
        $queryStr = $this->query_string;
        $orderBy = $this->order_by;

        $itemsCount = db()->find_one(
            "SELECT COUNT(*) FROM `user_service`
            LEFT JOIN `service`
            ON `user_service`.service_id = `service`.service_id
            LEFT JOIN `service_subcategory`
            ON `service`.service_subcategory_id = `service_subcategory`.service_subcategory_id
            LEFT JOIN `service_category`
            ON `service_subcategory`.service_category_id = `service_category`.service_category_id
            RIGHT JOIN `user`
            ON user_service.user_id = `user`.`user_id`
            WHERE $queryStr"
        );
        $itemsCount = $itemsCount['COUNT(*)'];

        $variables['pagination'] = $this->_getPagination($itemsCount);
        $limit = $variables['pagination']['limit'];
        $offset = $variables['pagination']['offset'];

        if ($offset > $itemsCount) {
            return false;
        }

        $services = db()->select(
            "SELECT
                `user`.`user_id`,
                `user`.`user_role`,
                `user`.`first_name`,
                `user`.`last_name`,
                `user`.`salon_name`,
                `user`.`contact_adress`,
                `user_service`.`user_service_id`,
                `user_service`.`user_service_name`,
                `user_service`.`user_service_rating`,
                `user_service`.`user_service_price`,
                `user_service`.`user_service_discount`,
                `user_service`.`user_service_discount_expire`,
                `user_service`.`user_service_time`,
                `user_service`.`time_unit`,
                `service`.`service_name`,
                `service`.`service_slug`,
                `service`.`lang_key`,
                `service_subcategory`.`service_subcategory_slug`,
                `service_subcategory`.`service_subcategory_name`,
                `service_subcategory`.`lang_key` AS subcategory_lang_key,
                `service_category`.`service_category_slug`
            FROM `user_service`
            LEFT JOIN `service`
            ON `user_service`.service_id = `service`.service_id
            LEFT JOIN `service_subcategory`
            ON `service`.service_subcategory_id = `service_subcategory`.service_subcategory_id
            LEFT JOIN `service_category`
            ON `service_subcategory`.service_category_id = `service_category`.service_category_id
            RIGHT JOIN `user`
            ON user_service.user_id = `user`.`user_id`
            WHERE $queryStr
            ORDER BY $orderBy
            LIMIT $limit
            OFFSET $offset"
        );

        $variables['sellers'] = [];
        foreach ($services as $service) {
            $variables['sellers'][] = new CatalogServiceModel($service, false);
        }

        $variables['currency'] = $this->currency;

        $breadcrumbs = [
            [
                'title' => get_text('home'),
                'link' => '/',
                'active' => false
            ],
            [
                'title' => get_text('service_catalog'),
                'link' => '/catalog',
                'active' => false
            ],
            [
                'title' => $categoryTitle,
                'link' => "/catalog/$categorySlug",
                'active' => false
            ],
            [
                'title' => $subcategoryTitle,
                'link' => "/catalog/$categorySlug/$subcategorySlug",
                'active' => false
            ],
            [
                'title' => $tagTitle,
                'link' => "/catalog/$categorySlug/$subcategorySlug/$tagSlug",
                'active' => true
            ]
        ];

        $titleBread = [get_text('all_services'), $categoryTitle, $subcategoryTitle, $tagTitle];

        $this->model = new PageModel($tagTitle . ' / ' . $subcategoryTitle . ' / ' . $categoryTitle . ' / ' . get_text('service_catalog'), new PurpleModel('content/catalog', $variables), get_text('service_catalog'), $breadcrumbs, '', 'default-page-wrapper', "/catalog/$categorySlug/$subcategorySlug", $titleBread);
        $this->model->load();
    }
    
    private function _getFiltersQuery(array $defaultQuery, string $defaultOrderBy)
    {
        $query = $defaultQuery;
        $orderBy = $defaultOrderBy;
        if(intval($_GET['filter']) === 1) {
            $city = search_input($_GET['city']);
            $street = search_input($_GET['street']);
            $rating = intval($_GET['rating']);
            $discounts = intval($_GET['discounts']);
            $sort = $_GET['sort'];
            $view = $_GET['view'];

            $price_from = floatval($_GET['price_from']);
            $price_to = floatval($_GET['price_to']);
            $service_duration = intval($_GET['service_duration']);

            $date_range = $_GET['date_range'];
            $time_from = intval($_GET['time_from']);
            $time_to = intval($_GET['time_to']);

            if(!empty($sort) and !in_array($sort, ['recommended', 'user_rating', 'price', 'discount', 'duration']) or !empty($view) and !in_array($view, ['master', 'salon']) or $rating > 0 and $rating > 5 or $service_duration > 0 and $service_duration > 4) {
                exit('data error');
            }

            if(!empty($sort)) {
                
                if($sort === 'recommended') {
                    $sort = 'user.recommended DESC';
                } else if($sort === 'user_rating') {
                    $sort = 'user_service.user_service_rating DESC';
                } else if($sort === 'price') {
                    $sort = 'user_service.user_service_price ASC';
                } else if($sort === 'discount') {
                    $sort = 'user_service.user_service_discount DESC';
                } else if($sort === 'duration') {
                    $sort = 'user_service.user_service_time ASC';
                }
                $orderBy = $sort;
            }

            
            $price = [];
            if($price_from > 0) {
                $price[] = "user_service.user_service_price >= $price_from";
            }
            if($price_to > 0 and $price_to > $price_from) {
                $price[] = "user_service.user_service_price <= $price_to";
            }
            if(!empty($price)) {
                $query['price'] = implode(" AND ", $price);
            }

            if($service_duration > 0) {
                switch ($service_duration) {
                    case 1:
                        $query['duration'] = "user_service.user_service_time <= 45";
                        break;
                    case 2:
                        $query['duration'] = "(user_service.user_service_time >= 45 AND user_service.user_service_time < 115)";
                        break;
                    case 3:
                        $query['duration'] = "(user_service.user_service_time >= 115 AND user_service.user_service_time < 180)";
                        break;
                    case 4:
                        $query['duration'] = "user_service.user_service_time >= 180";
                        break;
                }
            }

            if($discounts !== 0) {
                $time = time();
                $query['discounts'] = "user_service.user_service_discount_expire > $time";
            }

            if(!empty($view)) {
                $query['role'] = "user.user_role = '$view'";
            }

            if($rating > 0) {
                $maxRating = $rating+0.5;
                $query['rating'] = "(user_service.user_service_rating >= $rating AND user_service.user_service_rating <= $maxRating)";
            }

            if(!empty($city)) {
                $query['adress'] = "user.contact_adress LIKE '%$city%'";
                if(!empty($street)) {
                    $query['adress'] = '('.$query['adress']." AND user.contact_adress LIKE '%$street%')";
                }
            }
        }
        $queryStr = implode(' AND ', $query);
        $this->query_string = $queryStr;
        $this->order_by = $orderBy;

    }

    private function _getPagination(int $count): array
    {
        return getPagination($count);
    }
}
