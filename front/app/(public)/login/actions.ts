'use server';

import { apiFetch } from '@/lib/api';
import type { LoginSchema } from './schemas';

export async function login(formData: LoginSchema) {
  const datas = {
    email: formData.email,
    password: formData.password,
  };
  const res = apiFetch({
    url: '/login',
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
