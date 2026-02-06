let listContainer
let draggableItem
let pointerStartX
let pointerStartY
let itemsGap = 0
let items = []
let prevRect = {}

function getAllItems() {
  if (!items?.length) {
    items = Array.from(listContainer.querySelectorAll('.js-item'))
  }
  return items
}

function getIdleItems() {
  return getAllItems().filter((item) => item.classList.contains('is-idle'))
}

function isItemAbove(item) {
  return item.hasAttribute('data-is-above')
}

function isItemToggled(item) {
  return item.hasAttribute('data-is-toggled')
}

function setup() {
  listContainer = document.querySelector('.js-list')

  if (!listContainer) return

  listContainer.addEventListener('mousedown', dragStart)
  listContainer.addEventListener('touchstart', dragStart)

  document.addEventListener('mouseup', dragEnd)
  document.addEventListener('touchend', dragEnd)
}

function dragStart(e) {
  if (e.target.classList.contains('js-drag-handle')) {
    draggableItem = e.target.closest('.js-item')
  }

  if (!draggableItem) return

  pointerStartX = e.clientX || e.touches?.[0]?.clientX
  pointerStartY = e.clientY || e.touches?.[0]?.clientY

  setItemsGap()
  disablePageScroll()
  initDraggableItem()
  initItemsState()
  prevRect = draggableItem.getBoundingClientRect()

  document.addEventListener('mousemove', drag)
  document.addEventListener('touchmove', drag, { passive: false })
}

function setItemsGap() {
  if (getIdleItems().length <= 1) {
    itemsGap = 0
    return
  }

  const item1 = getIdleItems()[0]
  const item2 = getIdleItems()[1]

  const item1Rect = item1.getBoundingClientRect()
  const item2Rect = item2.getBoundingClientRect()

  itemsGap = Math.abs(item1Rect.bottom - item2Rect.top)
}

function disablePageScroll() {
  document.body.style.overflow = 'hidden'
  document.body.style.touchAction = 'none'
  document.body.style.userSelect = 'none'
}

function initItemsState() {
  getIdleItems().forEach((item, i) => {
    if (getAllItems().indexOf(draggableItem) > i) {
      item.dataset.isAbove = ''
    }
  })
}

function initDraggableItem() {
  draggableItem.classList.remove('is-idle')
  draggableItem.classList.add('is-draggable')
}

function drag(e) {
  if (!draggableItem) return

  e.preventDefault()

  const clientX = e.clientX || e.touches[0].clientX
  const clientY = e.clientY || e.touches[0].clientY

  const pointerOffsetX = clientX - pointerStartX
  const pointerOffsetY = clientY - pointerStartY

  draggableItem.style.transform = `translate(${pointerOffsetX}px, ${pointerOffsetY}px)`

  updateIdleItemsStateAndPosition()
}

function updateIdleItemsStateAndPosition() {
  const draggableItemRect = draggableItem.getBoundingClientRect()
  const draggableItemY = draggableItemRect.top + draggableItemRect.height / 2
  // Update state
  getIdleItems().forEach((item) => {
    const itemRect = item.getBoundingClientRect()
    const itemY = itemRect.top + itemRect.height / 2
    if (isItemAbove(item)) {
      if (draggableItemY <= itemY) {
        item.dataset.isToggled = ''
      } else {
        delete item.dataset.isToggled
      }
    } else {
      if (draggableItemY >= itemY) {
        item.dataset.isToggled = ''
      } else {
        delete item.dataset.isToggled
      }
    }
  })

  // Update position
  getIdleItems().forEach((item) => {
    if (isItemToggled(item)) {
      const direction = isItemAbove(item) ? 1 : -1
      item.style.transform = `translateY(${
        direction * (draggableItemRect.height + itemsGap)
      }px)`
    } else {
      item.style.transform = ''
    }
  })
}

function dragEnd(e) {
  if (!draggableItem) return

  applyNewItemsOrder(e)
  cleanup()
}

function applyNewItemsOrder(e) {
  const reorderedItems = []

  getAllItems().forEach((item, index) => {
    if (item === draggableItem) {
      return
    }
    if (!isItemToggled(item)) {
      reorderedItems[index] = item
      return
    }
    const newIndex = isItemAbove(item) ? index + 1 : index - 1
    reorderedItems[newIndex] = item
  })

  for (let index = 0; index < getAllItems().length; index++) {
    const item = reorderedItems[index]
    if (typeof item === 'undefined') {
      reorderedItems[index] = draggableItem
    }
  }

  reorderedItems.forEach((item) => {
    listContainer.appendChild(item)
  })
  
  const nodes = Array.from(listContainer.querySelectorAll('.js-item'))
  const order = nodes.map((n, i) => ({ index: i, id: n.dataset.id ?? n.id ?? null }))

  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

  fetch('/tasks/reorder', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify({ order }),
  })
  .then(async (res) => {
    if (!res.ok) {
      const err = await res.json().catch(() => ({}))
      throw err
    }
    return res.json()
  })
  .then((data) => {
    showSuccessToast('Reorder successfully saved')
  })
  .catch((err) => {
    console.error('reorder failed', err)
  })

  const finalIndex = nodes.indexOf(draggableItem)

  draggableItem.style.transform = ''

  requestAnimationFrame(() => {
    const rect = draggableItem.getBoundingClientRect()
    const yDiff = prevRect.y - rect.y
    const currentPositionX = e.clientX || e.changedTouches?.[0]?.clientX
    const currentPositionY = e.clientY || e.changedTouches?.[0]?.clientY
    const pointerOffsetX = currentPositionX - pointerStartX
    const pointerOffsetY = currentPositionY - pointerStartY

    draggableItem.style.transform = `translate(${pointerOffsetX}px, ${
      pointerOffsetY + yDiff
    }px)`
    requestAnimationFrame(() => {
      unsetDraggableItem()
    })
  })
}

function cleanup() {
  itemsGap = 0
  items = []
  unsetItemState()
  enablePageScroll()

  document.removeEventListener('mousemove', drag)
  document.removeEventListener('touchmove', drag)
}

function unsetDraggableItem() {
  draggableItem.style = null
  draggableItem.classList.remove('is-draggable')
  draggableItem.classList.add('is-idle')
  draggableItem = null
}

function unsetItemState() {
  getIdleItems().forEach((item, i) => {
    delete item.dataset.isAbove
    delete item.dataset.isToggled
    item.style.transform = ''
  })
}

function enablePageScroll() {
  document.body.style.overflow = ''
  document.body.style.touchAction = ''
  document.body.style.userSelect = ''
}

function showSuccessToast(message = 'Reorder successfully saved') {
  const container = document.getElementById('toast-container')
  const toast = document.createElement('div')
  toast.innerHTML = `
    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md flex items-start gap-3">
      <svg class="h-6 w-6 text-teal-500 flex-shrink-0 mt-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
      </svg>

      <div>
        <p class="font-bold">Success</p>
        <p class="text-sm">${message}</p>
      </div>
    </div>
  `
  toast.className = 'transition transform duration-300 ease-out opacity-0 translate-x-4'
  container.appendChild(toast)

  requestAnimationFrame(() => {
    toast.classList.remove('opacity-0', 'translate-x-4')
  })

  setTimeout(() => {
    toast.classList.add('opacity-0', 'translate-x-4')
    setTimeout(() => toast.remove(), 300)
  }, 3000)
}

setup()