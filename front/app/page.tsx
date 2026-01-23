export default async function Home() {
  // TODO: 消す。疎通チェック用。
  // const res = await fetch(`${process.env.API_BASE_URL}/articles`, {
  //   method: 'GET',
  //   headers: {
  //     'Content-Type': 'application/json',
  //     Accept: 'application/json',
  //   },
  // });
  // console.log(await res.json());

  // TODO: 消す（TSServerテスト用）
  // let ppp: readonly number[] = [1, 2, 3];
  // ppp = [2, 3, 4];
  // ppp[0] = 1;
  // console.log(ppp);

  return <div>HOME</div>;
}
