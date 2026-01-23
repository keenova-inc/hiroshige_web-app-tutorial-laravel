import { z } from 'zod';
import fields from '@/lang/ja/fields';
import { makeErrorMessage, messages } from '@/lang/ja/validation';

export const confirmPasswordSchema = z
  .string()
  .min(
    1,
    makeErrorMessage({ message: messages.required, params: { field: fields.confirmPassword } }),
  );
