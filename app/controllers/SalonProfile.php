<?php

namespace App\Controllers;

use App\Classes\FileUpload;
use App\Classes\Menu\Menu;
use App\Classes\Menu\MenuItem;
use App\Classes\Thumbs;
use App\System\Core\Controller;
use Themes\Purple\Models\PageModel;
use Themes\Purple\Models\PurpleModel;

use const App\ROOT\ABSPATH;
use const App\ROOT\SYSTEM_MODE;

use function App\db;
use function App\get_request_url;
use function App\get_text;
use function App\getUserAvatar;
use function App\is_admin;
use function App\is_logged_in;
use function App\redirect;
use function App\user;

class SalonProfile extends Controller
{

    function __construct()
    {
        if (SYSTEM_MODE === 'production') {
            if (is_admin() and get_request_url() === 'profile/settings') {
                redirect('admin/profile/settings');
            }
            if (is_admin()) {
                redirect('admin');
            }
            if (!is_logged_in() or user()->user_role !== 'master' and user()->user_role !== 'salon') {
                redirect('sign_in');
            }
        } else if (!is_logged_in() and !is_admin()) {
            redirect('sign_in');
        }

        $this->variables['profile_avatar'] = getUserAvatar(user()->user_avatar, user()->full_name);
        $this->variables['data_menu'] = new Menu(
            'data_menu',
            new MenuItem(get_text('contacts'), '/salon/contacts', '', '', 'active', ''),
            new MenuItem(get_text('services'), '/profile/services', '', '', 'active', ''),
            new MenuItem(get_text('payments'), '/profile/payments', '', '', 'active', ''),
        );
        $this->variables['profile_menu'] = new Menu(
            'profile_menu',
            new MenuItem(get_text('data_and_services'), '/salon/contacts', '', '', 'active', ''),
            new MenuItem(get_text('personal'), '/salon/personal', '', '', 'active', ''),
            new MenuItem(get_text('clients'), '/salon/clients', '', '', 'active', ''),
            new MenuItem(get_text('reviews'), '/salon/reviews', '', '', 'active', ''),
            new MenuItem(get_text('stats'), '/salon/stats', '', '', 'active', ''),
            new MenuItem(get_text('promotion'), '/salon/promotion', '', '', 'active', '')
        );

        if (isset($_FILES['photo_upload']) and exif_imagetype($_FILES['photo_upload']['name'])) {

            $current_user_id = user()->user_id;
            $folderPath = "/assets/uploads/$current_user_id";
            $folder = ABSPATH . $folderPath;

            $photo = new FileUpload($_FILES['photo_upload'], $folderPath, IMAGE_MIME_TYPES, 5, false);
            $photo->upload();
            $image = new Thumbs(ABSPATH . '/' . $photo->uploadPath);
            $image->resize(800, 0);
            $image->cut(640, 640);
            $fname = time() . '-800.png';
            $fpath = $folderPath . '/' . $fname;
            $image->save($folder . '/' . $fname);

            db()->update('user', ['user_avatar' => $fpath], $current_user_id);
            header('Location: /' . get_request_url());
            exit;
        }

        $this->loadTheme('purple');
    }

    public function contacts()
    {
        $variables = [];
        $this->_loadModel('content/contacts', get_text('contacts'), $variables);
    }
    
    public function services()
    {
        $variables = [];
        $this->_loadModel('content/services', get_text('services'), $variables);
    }
    
    public function payments()
    {
        $variables = [];
        $this->_loadModel('content/payments', get_text('payments'), $variables);
    }
    
    public function personal()
    {
        $variables = [];
        $this->_loadModel('content/personal', get_text('promition'), $variables);
    }
    
    public function employee()
    {
        $variables = [];
        $employeeName = 'master name';
        $this->_loadModel('content/employee', $employeeName, $variables);
    }
    
    public function clients()
    {
        $variables = [];
        $this->_loadModel('content/clients', get_text('clients'), $variables);
    }
  
    public function reviews()
    {
        $variables = [];
        $this->_loadModel('content/reviews', get_text('reviews'), $variables);
    }
    
    public function stats()
    {
        $variables = [];
        $this->_loadModel('content/stats', get_text('stats'), $variables);
    }
    
    public function promotion()
    {
        $variables = [];
        $this->_loadModel('content/promotion', get_text('promition'), $variables);
    }
    
    public function blacklist()
    {
        $variables = [];
        $this->model = new PageModel(get_text('blacklist'), new PurpleModel('salon/blacklist', $variables), breadcrumbs: null, display_title: false);
        $this->model->load();
    }
      
    public function client()
    {
        $variables = [];
        $clientName = 'client name';
        $breadcrumbs = [
            [
                'title' => get_text('home'),
                'link' => '/',
                'active' => false
            ],
            [
                'title' => get_text('client_profile'),
                'link' => '',
                'active' => true,
                'arrow' => true
            ],
            [
                'title' => $clientName,
                'link' => '',
                'active' => true
            ]
        ];
        $this->model = new PageModel($clientName, new PurpleModel('salon/client', $variables), breadcrumbs: $breadcrumbs, display_title: false);
        $this->model->load();
    }
    

    private function _loadModel(string $viewName, string $title, array $variables = [])
    {
        $breadcrumbs = [
            [
                'title' => get_text('home'),
                'link' => '/',
                'active' => false
            ],
            [
                'title' => get_text('salon_profile'),
                'link' => '',
                'active' => true,
                'arrow' => true
            ],
            [
                'title' => $title,
                'link' => '',
                'active' => true
            ]
        ];
        $variables['profile_content'] = new PurpleModel($viewName, $variables);
        $variables['profile_content_title'] = $title;
        $this->model = new PageModel($title, new PurpleModel('salon/layout', $variables), breadcrumbs: $breadcrumbs, display_title: false);
        $this->model->load();
    }
}
