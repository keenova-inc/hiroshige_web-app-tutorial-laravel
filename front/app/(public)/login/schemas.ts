import { z } from 'zod';
import fields from '@/lang/ja/fields';
import { makeErrorMessage, messages } from '@/lang/ja/validation';

export const loginEmailSchema = z
  .email(makeErrorMessage({ message: messages.email, params: { field: fields.email } }))
  .min(1, makeErrorMessage({ message: messages.required, params: { field: fields.email } }));

export const loginPasswordSchema = z
  .string()
  .min(1, makeErrorMessage({ message: messages.required, params: { field: fields.password } }));

export const loginSchema = z.object({
  email: loginEmailSchema,
  password: loginPasswordSchema,
});

export type LoginSchema = z.infer<typeof loginSchema>;
