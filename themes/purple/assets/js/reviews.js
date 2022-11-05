window.onload = () => {
  let width = window.innerWidth;
  let lastWidth = null;

  const grid = document.querySelector('.slider-reviews-track');

  let msnry = new Masonry(grid, {
    itemSelector: '.review-item',
    columnWidth: '.review-item',
    gutter: 20,
    transitionDuration: 0
  });


  let slider = document.querySelector('.slider-reviews'),
  sliderList = slider.querySelector('.slider-reviews-list'),
  sliderTrack = slider.querySelector('.slider-reviews-track'),
  slides = slider.querySelectorAll('.slide-review'),
  slideWidth = slides[0].offsetWidth + 10,
  slideIndex = 0,
  posInit = 0,
  posX1 = 0,
  posX2 = 0,
  posY1 = 0,
  posY2 = 0,
  posFinal = 0,
  isSwipe = false,
  isScroll = false,
  allowSwipe = true,
  transition = true,
  nextTrf = 0,
  prevTrf = 0,
  lastTrf = --slideWidth,
  posThreshold = slides[0].offsetWidth * 0.35,
  trfRegExp = /([-0-9.]+(?=px))/,


  getEvent = function () {
    return (event.type.search('touch') !== -1) ? event.touches[0] : event;
  },

  slide = function () {
    if (transition) {
      sliderTrack.style.transition = 'transform .5s';
    }
    sliderTrack.style.transform = `translate3d(-${slideIndex * (slides[0].offsetWidth + 10)}px, 0px, 0px)`;
  },

  swipeStart = function () {
    let evt = getEvent();

    if (allowSwipe) {

      transition = true;

      nextTrf = (slideIndex + 1) * -slideWidth;
      prevTrf = (slideIndex - 1) * -slideWidth;

      posInit = posX1 = evt.clientX;
      posY1 = evt.clientY;

      sliderTrack.style.transition = '';

      document.addEventListener('touchmove', swipeAction);
      document.addEventListener('mousemove', swipeAction);
      document.addEventListener('touchend', swipeEnd);
      document.addEventListener('mouseup', swipeEnd);

      sliderList.classList.remove('grab');
      sliderList.classList.add('grabbing');
    }
  },

  swipeAction = function () {

    let evt = getEvent();
    let style = sliderTrack.style.transform;
    let transform = +style.match(trfRegExp)[0];

    posX2 = posX1 - evt.clientX;
    posX1 = evt.clientX;

    posY2 = posY1 - evt.clientY;
    posY1 = evt.clientY;

    isScroll = true;
    isSwipe = true;

    if (isSwipe) {
      if (slideIndex === 0) {
        if (posInit < posX1) {
          setTransform(transform, 0);
          return;
        } else {
          allowSwipe = true;
        }
      }

      if (slideIndex === --slides.length) {
        if (posInit > posX1) {
          setTransform(transform, lastTrf);
          return;
        } else {
          allowSwipe = true;
        }
      }

      sliderTrack.style.transform = `translate3d(${transform - posX2}px, 0px, 0px)`;
    }
  },

  swipeEnd = function () {
    posFinal = posInit - posX1;

    isScroll = false;
    isSwipe = false;

    document.removeEventListener('touchmove', swipeAction);
    document.removeEventListener('mousemove', swipeAction);
    document.removeEventListener('touchend', swipeEnd);
    document.removeEventListener('mouseup', swipeEnd);

    sliderList.classList.add('grab');
    sliderList.classList.remove('grabbing');

    if (allowSwipe) {
      if (Math.abs(posFinal) > posThreshold) {
        if (posInit < posX1) {
          slideIndex--;
        } else if (posInit > posX1) {
          slideIndex++;
        }
      }

      if (posInit !== posX1) {
        allowSwipe = false;
        slide();
      } else {
        allowSwipe = true;
      }

    } else {
      allowSwipe = true;
    }
  },

  setTransform = function (transform, comapreTransform) {
    if (transform >= comapreTransform) {
      if (transform > comapreTransform) {
        sliderTrack.style.transform = `translate3d(${comapreTransform}px, 0px, 0px)`;
      }
    }
    allowSwipe = false;
  },
    
  changeStateSlider = function (width, lastWidth) {
    if ((lastWidth > 680 || lastWidth == null) && width <= 680) {
      msnry.destroy();
      slider.removeAttribute('style');
      sliderTrack.removeAttribute('style');

      sliderTrack.addEventListener('transitionend', () => allowSwipe = true);
      slider.addEventListener('touchstart', swipeStart);
      slider.addEventListener('mousedown', swipeStart);

      slide();

    } else if (lastWidth <= 680 && width > 680) {

      slider.removeAttribute('style');
      sliderTrack.removeAttribute('style');

      document.removeEventListener('touchmove', swipeAction);
      document.removeEventListener('mousemove', swipeAction);
      document.removeEventListener('touchend', swipeEnd);
      document.removeEventListener('mouseup', swipeEnd);

      renderReview();
    }

    lastWidth = width;
  },

  addUncoverBtn = function (review) {
    let contentBlock = review.querySelector('.review-item-text');

    if (contentBlock.clientHeight > 89) {
      contentBlock.classList.add('review-item-text-shadow');
      review.insertAdjacentHTML("beforeend", '<button class="review-uncover-button">читать полностью</button>');
    }
  }

  generateHeightParam = function (arrBtnUncover) {
    let parentBlock = arrBtnUncover.parentElement;
    let contentBlock = parentBlock.querySelector('.review-item-text');
    let newHeight = parentBlock.clientHeight - 89 + contentBlock.scrollHeight + 'px';

    if (!parentBlock.classList.contains('review-with-photo')) {
      parentBlock.style.setProperty('--element-height', '191px');
    } else {
      parentBlock.style.setProperty('--element-height', '652px');
    }

    parentBlock.style.setProperty('--element-next-height', newHeight);
  },

  renderReview = function () {
    msnry = new Masonry(grid, {
      itemSelector: '.review-item',
      columnWidth: '.review-item',
      gutter: 20,
      transitionDuration: 0
    });
  },
      
  addEventUnvocerReview = function (uncoverBtn) {
    uncoverBtn.addEventListener('click', function (e) {
      e.preventDefault();

      let parentBlock = uncoverBtn.parentElement;
      let contentBlock = parentBlock.querySelector('.review-item-text');

      contentBlock.classList.toggle('review-item-text-shadow');

      if (!contentBlock.classList.contains('review-item-text-shadow')) {
        uncoverBtn.innerText = 'Скрыть';
      } else {
        uncoverBtn.innerText = 'Читать полностью';
      }

      if (window.innerWidth > 680) {
        let parentBlockStyle = window.getComputedStyle(parentBlock, null);
        let height = parentBlockStyle.getPropertyValue("--element-height");
        let nextHeight = parentBlockStyle.getPropertyValue("--element-next-height");

        parentBlock.style.setProperty('--element-height', nextHeight);
        parentBlock.style.setProperty('--element-next-height', height);

        const intervalId = setInterval(renderReview, 20);
        setTimeout(() => clearInterval(intervalId), 400);
      } else {
        var modal = $modal({
          title: 'Текст заголовка',
          content: '<p>Содержимое модального окна...</p>',
          footerButtons: [
            { class: 'btn btn__cancel', text: 'Отмена', handler: 'modalHandlerCancel' },
            { class: 'btn btn__ok', text: 'ОК', handler: 'modalHandlerOk' }
          ]
        });
        modal.show();
      }
    });
  }

  for (let i = 0; i < slides.length; i++) {
    addUncoverBtn(slides[i]);
  }

  let arrBtnUncover = document.querySelectorAll('.review-uncover-button');

  for (let i = 0; i < arrBtnUncover.length; i++) {
    generateHeightParam(arrBtnUncover[i]);
    addEventUnvocerReview(arrBtnUncover[i]);
  }

  window.onresize = (e) => {
    let width = e.target.innerWidth;
    changeStateSlider(width, lastWidth);
  }

  changeStateSlider(width, lastWidth);
}