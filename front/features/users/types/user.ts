export type User = {
  id: number;
  name: string;
  email: string;
  email_verified_at?: string;
  two_factor_secret?: string;
  two_factor_recovery_codes?: string;
  two_factor_confirmed_at?: string;
};
