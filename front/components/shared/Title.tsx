type Props = {
  children: React.ReactNode;
};

export default function Title({ children }: Props) {
  return <p className="flex items-center gap-2 text-3xl font-bold">{children}</p>;
}
