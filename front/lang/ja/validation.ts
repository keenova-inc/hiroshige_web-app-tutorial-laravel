export const messages = {
  required: '{field}は必須です。',
  confirmed: '{field}が一致しません。',
  email: '無効な{field}です。',
  maxString: '{field}は、{max}文字以下の文字列である必要があります。',
  minString: '{field}は、{min}文字以上の文字列である必要があります。',
  // TODO: Laravel側のmin.stringのメッセージが妙
} as const;

type Message = (typeof messages)[keyof typeof messages];

type Params = {
  message: Message;
  params: { [key: string]: string | number };
};

export const makeErrorMessage = ({ message, params }: Params): string =>
  Object.entries(params).reduce(
    (result: string, [key, value]) => result.replace(`{${key}}`, String(value)),
    message,
  );
