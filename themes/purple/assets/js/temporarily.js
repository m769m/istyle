// window.onload = () => {
$(document).ready(function () {
  let sliderX = document.querySelector('.slider-photos-reviews'),
    sliderListX = sliderX.querySelector('.slider-photos-list'),
    sliderTrackX = sliderX.querySelector('.slider-photos-track'),
    slidesX = sliderX.querySelectorAll('.slider-photo-container'),
    prev = sliderX.querySelector('.prev'),
    next = sliderX.querySelector('.next'),
    slideWidthX = slidesX[0].offsetWidth + 20,
    slideIndexX = 0,
    posInitX = 0,
    posX1X = 0,
    posX2X = 0,
    posY1X = 0,
    posY2X = 0,
    posFinalX = 0,
    isSwipeX = false,
    isScrollX = false,
    allowSwipeX = true,
    transitionX = true,
    nextTrfX = 0,
    prevTrfX = 0,
    lastTrfX = --slideWidthX,
    posThresholdX = slidesX[0].offsetWidth * 0.35,
    trfRegExpX = /([-0-9.]+(?=px))/,


    getEvent = function () {
      return (event.type.search('touch') !== -1) ? event.touches[0] : event;
    },

    slide = function () {
      if (transitionX) {
        sliderTrackX.style.transition = 'transform .5s';
      }
      sliderTrackX.style.transform = `translate3d(-${slideIndexX * (slidesX[0].offsetWidth + 20)}px, 0px, 0px)`;

      prev.classList.toggle('disabled', slideIndexX === 0);
      next.classList.toggle('disabled', slideIndexX === --slidesX.length);
    },

    swipeStart = function () {
      let evt = getEvent();

      if (allowSwipeX) {
        swipeStartTime = Date.now();

        transitionX = true;

        nextTrfX = (slideIndexX + 1) * -slideWidthX;
        prevTrfX = (slideIndexX - 1) * -slideWidthX;

        posInitX = posX1X = evt.clientX;
        posY1X = evt.clientY;

        sliderTrackX.style.transition = '';

        document.addEventListener('touchmove', swipeAction);
        document.addEventListener('mousemove', swipeAction);
        document.addEventListener('touchend', swipeEnd);
        document.addEventListener('mouseup', swipeEnd);

        sliderListX.classList.remove('grab');
        sliderListX.classList.add('grabbing');
      }
    },

    swipeAction = function () {

      let evt = getEvent();
      let style = sliderTrackX.style.transform;
      let transform = +style.match(trfRegExpX)[0];

      posX2X = posX1X - evt.clientX;
      posX1X = evt.clientX;

      posY2X = posY1X - evt.clientY;
      posY1X = evt.clientY;

      isScrollX = true;
      isSwipeX = true;

      if (isSwipeX) {
        if (slideIndexX === 0) {
          if (posInitX < posX1X) {
            setTransform(transform, 0);
            return;
          } else {
            allowSwipeX = true;
          }
        }

        if (slideIndexX === --slidesX.length) {
          if (posInitX > posX1X) {
            setTransform(transform, lastTrfX);
            return;
          } else {
            allowSwipeX = true;
          }
        }

        sliderTrackX.style.transform = `translate3d(${transform - posX2X}px, 0px, 0px)`;
      }
    },

    swipeEnd = function () {
      posFinalX = posInitX - posX1X;

      isScrollX = false;
      isSwipeX = false;

      document.removeEventListener('touchmove', swipeAction);
      document.removeEventListener('mousemove', swipeAction);
      document.removeEventListener('touchend', swipeEnd);
      document.removeEventListener('mouseup', swipeEnd);

      sliderListX.classList.add('grab');
      sliderListX.classList.remove('grabbing');

      if (allowSwipeX) {
        swipeEndTime = Date.now();
        if (Math.abs(posFinalX) > posThresholdX || swipeEndTime - swipeStartTime < 300) {
          if (posInitX < posX1X) {
            slideIndexX--;
          } else if (posInitX > posX1X) {
            slideIndexX++;
          }
        }

        if (posInitX !== posX1X) {
          allowSwipeX = false;
          slide();
        } else {
          allowSwipeX = true;
        }

      } else {
        allowSwipeX = true;
      }
    },

    setTransform = function (transform, comapreTransform) {
      if (transform >= comapreTransform) {
        if (transform > comapreTransform) {
          sliderTrackX.style.transform = `translate3d(${comapreTransform}px, 0px, 0px)`;
        }
      }
      allowSwipeX = false;
    }

  sliderTrackX.style.transform = 'translate3d(0px, 0px, 0px)';
  sliderListX.classList.add('grab');

  sliderTrackX.addEventListener('transitionend', () => allowSwipeX = true);
  sliderX.addEventListener('touchstart', swipeStart);
  sliderX.addEventListener('mousedown', swipeStart);

  prev.addEventListener('click', function () {
    slideIndexX--;
    slide();
  });

  next.addEventListener('click', function () {
    slideIndexX++;
    slide();
  });
});