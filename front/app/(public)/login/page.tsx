import Title from '@/components/shared/Title';
import { LoginForm } from './components/login-form';

export default function LoginPage() {
  return (
    <>
      <Title>ログイン</Title>
      <div className="bg-muted flex flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div className="flex w-full max-w-sm flex-col min-w-90">
          <LoginForm />
        </div>
      </div>
    </>
  );
}
