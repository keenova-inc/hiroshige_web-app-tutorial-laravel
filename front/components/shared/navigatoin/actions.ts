'use server';

import { cookies } from 'next/headers';
import messages from '@/lang/ja/messages';
import { apiFetch } from '@/lib/api';

export async function logout() {
  try {
    const res = await apiFetch({ url: '/logout', options: { method: 'POST' }, hasPrefix: false });
    const resJson = (await res.json()) as { message: string };
    if (res.status === 200) {
      // Cookieを削除
      const cookieStore = await cookies();
      const regex = /laravel*/;
      const sessionCooikie = cookieStore.getAll().find((c) => regex.test(c.name) === true);
      if (!sessionCooikie) {
        return {
          status: 401,
          messages: resJson.message,
        };
      }
      cookieStore.delete(sessionCooikie.name);
      cookieStore.delete('XSRF-TOKEN');

      return {
        status: res.status,
        messages: resJson.message,
      };
    } else {
      return {
        status: res.status,
        messages: resJson.message,
      };
    }
  } catch (e) {
    console.error(e);
    return {
      status: 500,
      messages: messages.networkError,
    };
  }
}
