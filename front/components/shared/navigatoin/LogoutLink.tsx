'use client';

import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { toast } from 'sonner';
import { NavigationMenuLink } from '@/components/ui/navigation-menu';
import { useLoading } from '@/contexts/LoadingContext';
import messages from '@/lang/ja/messages';
import { logout } from './actions';

export default function LogoutLink() {
  const router = useRouter();

  const { setLoading } = useLoading();

  async function onSubmit() {
    setLoading(true);
    try {
      const res = await logout();
      if (res.status === 200) {
        toast.success(res.messages);
        router.push('/login');
      } else {
        console.error(res);
        toast.error(messages.serverError);
      }
    } catch (e) {
      console.log(e);
      toast.error(messages.networkError);
    } finally {
      setLoading(false);
    }
  }

  return (
    <NavigationMenuLink asChild>
      <button type="button" onClick={onSubmit}>
        ログアウト
      </button>
    </NavigationMenuLink>
  );
}
