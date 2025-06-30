import { ref } from './ref';
import { Seconds } from '../schema/common';

export const RetryAfterHeader = ref.header('RetryAfter', {
  schema: Seconds,
  required: true,
  description: 'Когда следует повторить запрос.',
});
