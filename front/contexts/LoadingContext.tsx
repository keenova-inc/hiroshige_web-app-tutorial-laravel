'use client';

import { createContext, useContext, useEffect, useState } from 'react';
import { Loading } from '@/components/shared/Loading';

const LoadingContext = createContext({
  loading: false,
  setLoading: (_: boolean) => {},
});

export const LoadingProvider = ({ children }: { children: React.ReactNode }) => {
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    // スクロールロック
    if (loading) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = 'unset';
    }
  }, [loading]);

  return (
    <LoadingContext.Provider value={{ loading, setLoading }}>
      {children}
      {loading && <Loading />}
    </LoadingContext.Provider>
  );
};

export const useLoading = () => useContext(LoadingContext);
