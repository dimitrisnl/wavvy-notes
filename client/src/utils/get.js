/* eslint-disable import/prefer-default-export */
import { get } from 'lodash'

export const getData = obj => get(obj, 'data')
