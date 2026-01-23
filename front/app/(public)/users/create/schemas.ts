import { z } from 'zod';
import { confirmPasswordSchema } from '@/features/users/schemas/confirmPassword';
import { emailSchema } from '@/features/users/schemas/email';
import { passwordSchema } from '@/features/users/schemas/password';
import { userNameSchema } from '@/features/users/schemas/userName';
import fields from '@/lang/ja/fields';
import { makeErrorMessage, messages } from '@/lang/ja/validation';

export const createUserSchema = z
  .object({
    name: userNameSchema,
    email: emailSchema,
    password: passwordSchema,
    password_confirmation: confirmPasswordSchema,
  })
  .refine((data) => data.password === data.password_confirmation, {
    message: makeErrorMessage({ message: messages.confirmed, params: { field: fields.password } }),
    path: ['password_confirmation'],
  });

export type CreateUserSchema = z.infer<typeof createUserSchema>;
