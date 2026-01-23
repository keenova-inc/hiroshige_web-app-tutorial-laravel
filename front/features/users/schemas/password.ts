import { z } from 'zod';
import fields from '@/lang/ja/fields';
import { makeErrorMessage, messages } from '@/lang/ja/validation';

export const min = 8;
export const passwordSchema = z
  .string()
  .min(
    min,
    makeErrorMessage({ message: messages.minString, params: { field: fields.password, min } }),
  );

export type PasswordSchema = z.infer<typeof passwordSchema>;
