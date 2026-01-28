'use server';

import { apiFetch } from '@/lib/api';

// TODO: 製造中
export async function getUrl(formData: FormData) {
  const dates = {
    createStart: formData.get('createStart'),
    createEnd: formData.get('createEnd'),
    updateStart: formData.get('updateStart'),
    updateEnd: formData.get('updateEnd'),
  };

  const dateQueryString = Object.entries(dates).reduce((accumulator, [key, value]) => {
    // console.log([key, value]);
    if (value === '') {
      return accumulator;
    } else {
      return `${accumulator}&${key}=${String(value).trim().split('T')[0]}`;
    }
  }, '');

  const searchWords = String(formData.get('searchWords')).trim();

  if (searchWords === '') {
    console.log(dateQueryString);
    return;
  }
  console.log(`${dateQueryString}&searchWords=${searchWords}`);
}

export async function search() {
  console.log('EXECUTE SEARCH');
  return await apiFetch({
    url: '/articles',
    options: {
      method: 'GET',
    },
  });
}
