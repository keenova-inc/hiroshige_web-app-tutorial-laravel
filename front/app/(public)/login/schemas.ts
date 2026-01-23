import { z } from 'zod';
import { emailSchema } from '@/features/users/schemas/email';
import { passwordSchema } from '@/features/users/schemas/password';

export const loginSchema = z.object({
  email: emailSchema,
  password: passwordSchema,
});

export type LoginSchema = z.infer<typeof loginSchema>;
