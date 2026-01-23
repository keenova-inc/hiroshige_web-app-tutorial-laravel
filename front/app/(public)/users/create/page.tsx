import Title from '@/components/shared/Title';
import { SignupForm } from './components/signup-form';

export default function SignupPage() {
  return (
    <>
      <Title>ユーザ登録</Title>
      <div className="bg-muted flex flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div className="flex w-full max-w-sm flex-col min-w-90">
          <SignupForm />
        </div>
      </div>
    </>
  );
}
