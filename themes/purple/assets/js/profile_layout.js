window.addEventListener('DOMContentLoaded', () => {

  listenToggleNotice()

  const menuItems = document.querySelectorAll('.profile-menu-item_mb')

  const menuItemsWords = Array.from(menuItems).reduce((newArr, cur) => {
    newArr.push(cur.innerHTML)
    return newArr
  }, [])

  const titleFrom = document.querySelector('.profile-content')
  const contentTitle = document.querySelector('.profile-content-title-block')
  const titleTo = document.querySelector('.wrapper-service-catalog')
  const blockBeforeNotice = document.querySelector('.profile-info')
  const noticesBox = document.querySelector('.notice-wrap-js-block')
  const noticeAfterBlock = document.querySelector('.back-button')

  // вставляю изображения для моб. плашки в лич. кабинете
  if (document.documentElement.clientWidth < 1025) {
    menuItems.forEach((el, idx) => {
      el.innerHTML = `<img src="/themes/purple/assets/images/profile-menu-mb-${idx + 1}.svg"/>`
    })
  }

  if (document.documentElement.clientWidth < 771) {
    if (contentTitle) titleTo.prepend(contentTitle)
    if (noticesBox) noticeAfterBlock.after(noticesBox)
  }

  // те же манипуляции с вёрсткой, но в динамике - при изменении разрешения + возвращение обратно
  window.addEventListener('resize', (e) => {
    if (document.documentElement.clientWidth < 1025) {
      menuItems.forEach((el, idx) => {
        el.innerHTML = `<img src="/themes/purple/assets/images/profile-menu-mb-${idx + 1}.svg"/>`
      })

      if (contentTitle) titleTo.prepend(contentTitle)
    } else {
      for (let i = 0; i < menuItemsWords.length; i++) {
        menuItems[i].innerHTML = menuItemsWords[i]
      }

      if (contentTitle) titleFrom.prepend(contentTitle)
    }

    if (document.documentElement.clientWidth < 771) {
      if (contentTitle) titleTo.prepend(contentTitle)
      if (noticesBox) noticeAfterBlock.after(noticesBox)

    } else {
      if (contentTitle) titleFrom.prepend(contentTitle)
      if (noticesBox) blockBeforeNotice.after(noticesBox)
    }

  })

})

function listenToggleNotice() {
  const noticeToggleButton = document.querySelector('.notice-mb-button')
  const closingBgModal = document.querySelector('.notice-popup-bg-close')

  const noticesModal = document.querySelector('.notices-box_mb')


  noticeToggleButton.addEventListener('click', () => {
    noticesModal.classList.add('notices-box_active')
    closingBgModal.classList.add('notice-popup-bg-close_active')
  })

  closingBgModal.addEventListener('click', () => {
    noticesModal.classList.remove('notices-box_active')
    closingBgModal.classList.remove('notice-popup-bg-close_active')
  })
}