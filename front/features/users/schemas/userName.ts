import { z } from 'zod';
import fields from '@/lang/ja/fields';
import { makeErrorMessage, messages } from '@/lang/ja/validation';

export const max = 100;
export const userNameSchema = z
  .string()
  .min(1, makeErrorMessage({ message: messages.required, params: { field: fields.userName } }))
  .max(
    max,
    makeErrorMessage({
      message: messages.maxString,
      params: {
        field: fields.userName,
        max,
      },
    }),
  );

export type UserNameSchema = z.infer<typeof userNameSchema>;
