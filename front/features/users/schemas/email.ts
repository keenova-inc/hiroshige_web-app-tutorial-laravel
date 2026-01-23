import { z } from 'zod';
import fields from '@/lang/ja/fields';
import { makeErrorMessage, messages } from '@/lang/ja/validation';

export const max = 255;

export const emailSchema = z
  .email(makeErrorMessage({ message: messages.email, params: { field: fields.email } }))
  .min(1, makeErrorMessage({ message: messages.required, params: { field: fields.email } }))
  .max(
    max,
    makeErrorMessage({
      message: messages.maxString,
      params: {
        field: fields.email,
        max,
      },
    }),
  );

export type EmailSchema = z.infer<typeof emailSchema>;
