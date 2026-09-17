'use client'

import { createContext, useContext, useEffect, useMemo, useState } from 'react'

const STORAGE_KEY = 'waste-services-cart-v1'
const CartContext = createContext(null)

export function CartProvider({ children }) {
  const [items, setItems] = useState([])
  const [ready, setReady] = useState(false)

  useEffect(() => {
    const restoreTimer = window.setTimeout(() => {
      try {
        const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]')
        if (Array.isArray(saved)) setItems(saved)
      } catch {}
      setReady(true)
    }, 0)
    return () => window.clearTimeout(restoreTimer)
  }, [])

  useEffect(() => {
    if (ready) localStorage.setItem(STORAGE_KEY, JSON.stringify(items))
  }, [items, ready])

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
  const clearCart = () => setItems([])
  const count = items.reduce((sum, item) => sum + item.quantity, 0)
  const total = items.reduce((sum, item) => sum + item.unitPrice * item.quantity, 0)
  const value = useMemo(() => ({ items, ready, count, total, addItems, updateQuantity, removeItem, clearCart }), [items, ready, count, total])

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>
}

export function useCart() {
  const value = useContext(CartContext)
  if (!value) throw new Error('useCart must be used inside CartProvider')
  return value
}
