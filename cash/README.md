# 플러그인 라이선스
- 라이선스는 LICENSE.md 를 참고 해주세요.

# 플러그인 설명 
- 캐쉬 플러그인은 서버 안에 캐쉬라는 새로운 개념입니다.
- /캐쉬 | 캐쉬 UI창이 뜹니다.
- /캐쉬관리 <주기,뺏기> | 행동할 수 있는 관리가 뜹니다.
- 아직 플러그인이 개발 중 입니다.
- 불편한점이 있다면 sungbin0099@naver.com으로 연락주세요.

# Instance설명
- cash::getInstance()->addCash($player, $amount); // 캐쉬를 줍니다.
- cash::getInstance()->setCash($player, $amount); // 캐쉬를 설정합니다.
- cash::getInstance()->removeCash($player, $amount); // 캐쉬를 뺏습니다.
- cash::getInstance()->seeCash($player); // 캐쉬를 봅니다.