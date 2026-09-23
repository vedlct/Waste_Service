'use client'

import { createContext, useContext, useEffect, useMemo, useState } from 'react'

const STORAGE_KEY = 'waste-services-cart-v1'
const COLLECTION_STORAGE_KEY = 'waste-services-collection-v1'
const CartContext = createContext(null)

// Items that can be sent to the booking API carry the backend catalogue type and id.
// Anything else in the cart (surcharges, or lines added before the site was wired to the
// API) is display-only and is priced server side.
export const BOOKABLE_TYPES = ['service_item', 'load_package']

export function isBookable(item) {
  return BOOKABLE_TYPES.includes(item.catalogueType) && Number.isInteger(item.catalogueId)
}

export function CartProvider({ children }) {
  const [items, setItems] = useState([])
  const [collection, setCollection] = useState(null)
  const [ready, setReady] = useState(false)

  useEffect(() => {
    const restoreTimer = window.setTimeout(() => {
      try {
        const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]')
        if (Array.isArray(saved)) setItems(saved)
      } catch {}
      try {
        const savedCollection = JSON.parse(localStorage.getItem(COLLECTION_STORAGE_KEY) || 'null')
        if (savedCollection && typeof savedCollection === 'object') setCollection(savedCollection)
      } catch {}
      setReady(true)
    }, 0)
    return () => window.clearTimeout(restoreTimer)
  }, [])

  useEffect(() => {
    if (ready) localStorage.setItem(STORAGE_KEY, JSON.stringify(items))
  }, [items, ready])

  useEffect(() => {
    if (!ready) return
    if (collection) localStorage.setItem(COLLECTION_STORAGE_KEY, JSON.stringify(collection))
    else localStorage.removeItem(COLLECTION_STORAGE_KEY)
  }, [collection, ready])

  const addItems = (incoming) => setItems((current) => {
    const next = [...current]
    incoming.forEach((item) => {
      const index = next.findIndex((existing) => existing.id === item.id)
      if (index >= 0) next[index] = { ...next[index], quantity: next[index].quantity + item.quantity }
      else next.push(item)
    })
    return next
  })
  const updateQuantity = (id, quantity) => setItems((current) => quantity < 1 ? current.filter((item) => item.id !== id) : current.map((item) => item.id === id ? { ...item, quantity } : item))
  const removeItem = (id) => setItems((current) => current.filter((item) => item.id !== id))
  const clearCart = () => {
    setItems([])
    setCollection(null)
  }
  const count = items.reduce((sum, item) => sum + item.quantity, 0)
  // An estimate only: the booking API reprices everything from the catalogue.
  const total = items.reduce((sum, item) => sum + item.unitPrice * item.quantity, 0)
  const value = useMemo(
    () => ({ items, ready, count, total, collection, setCollection, addItems, updateQuantity, removeItem, clearCart }),
    [items, ready, count, total, collection],
  )

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>
}

export function useCart() {
  const value = useContext(CartContext)
  if (!value) throw new Error('useCart must be used inside CartProvider')
  return value
}
