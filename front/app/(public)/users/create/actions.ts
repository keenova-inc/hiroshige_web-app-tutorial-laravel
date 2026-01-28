'use server';

import { apiFetch } from '@/lib/api';
import type { CreateUserSchema } from './schemas';

export async function createUser(formData: CreateUserSchema) {
  const datas = {
    name: formData.name,
    email: formData.email,
    password: formData.password,
    password_confirmation: formData.password_confirmation,
  };

  const res = apiFetch({
    url: '/users',
    options: {
      method: 'POST',
    },
    datas,
  });
  const resContents = await res;
  return {
    status: resContents.status,
    response: await resContents.json(),
  };
}
