import { describe, test } from 'node:test'
import assert from 'node:assert/strict'
import { coveredArea, outwardCode } from '../../src/lib/coverage.js'

const coverage = [
  { name: 'Portsmouth', areas: [{ name: 'Southsea', postcode_prefix: 'PO5' }, { name: 'Portsmouth City Centre', postcode_prefix: 'PO1' }] },
  { name: 'Fareham & Gosport', areas: [{ name: 'Fareham', postcode_prefix: 'po16' }, { name: 'Unmapped', postcode_prefix: null }] },
]

describe('outwardCode', () => {
  test('keeps the district of a full or partial postcode', () => {
    assert.equal(outwardCode('po5 1ab'), 'PO5')
    assert.equal(outwardCode('PO16 7GZ'), 'PO16')
    assert.equal(outwardCode('PO16'), 'PO16')
    assert.equal(outwardCode(''), '')
  })
})

describe('coveredArea', () => {
  test('names the area for a covered postcode', () => {
    assert.equal(coveredArea(coverage, 'PO5 1AB'), 'Southsea')
    assert.equal(coveredArea(coverage, 'po16 7gz'), 'Fareham')
  })

  test('matches the whole district, not a prefix of it', () => {
    assert.equal(coveredArea(coverage, 'PO19 1AA'), null)
    assert.equal(coveredArea(coverage, 'PO1 2AB'), 'Portsmouth City Centre')
  })

  test('is null outside the area, for blanks, or without coverage data', () => {
    assert.equal(coveredArea(coverage, 'SW1A 1AA'), null)
    assert.equal(coveredArea(coverage, '  '), null)
    assert.equal(coveredArea(null, 'PO5 1AB'), null)
  })
})
