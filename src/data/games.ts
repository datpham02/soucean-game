export interface Game {
  title: string;
  shortDescription: string;
  controls: string;
  cover: string;
  playLink: string;
}

export const games: Game[] = [
  {
    title: "Lamplight Vigil",
    shortDescription: "Một trò giải đố sinh tồn ngắn, nơi bạn giữ ngọn lửa canh trước gió biển đổi chiều.",
    controls: "Điều khiển: bàn phím",
    cover: "/games/lamplight-vigil/cover.svg",
    playLink: "/play/lamplight-vigil",
  },
];
