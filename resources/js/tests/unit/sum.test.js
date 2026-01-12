import { expect, test } from 'vitest'
import { sum } from '@chappy/testing/sum'

test('adds 1 + 2 to equal 3', () => {
  expect(sum(1, 2)).toBe(3)
})


test('adds 1 + 2 to equal 3 again', () => {
  expect(sum(1, 2)).toBe(3)
})


test('adds 1 + 2 to equal 3 3rd time', () => {
  expect(sum(1, 2)).toBe(3)
})