# SMonitor
서버를 가장 멋진 방법으로 모니터링해보세요.

<br>

## 체인지로그
* **0.0.1 (2017.10.16)** : 첫 릴리즈
* **0.0.2 (2017.11.12)** : 작은 버그 수정, 그래프 높이 5에서 10으로 변경

<br>

## 사용법
![](https://i.imgur.com/zgGOaQE.jpg)
![](https://i.imgur.com/aOZtvxH.jpg)
![](https://i.imgur.com/OiMAHHT.jpg)
![](https://i.imgur.com/uqBqLAd.jpg)

모니터링 가능한 항목에는 4가지가 있습니다.
* **TPS** : Ticks Per Second의 약자입니다. 20에 가까울수록 좋습니다. 서버 상태가 좋으면 20~18, 약간 좋지 않으면 18~15, 영 좋지 않으면 15~
* **Load** : CPU가 Load된 정도를 퍼센트로 표시합니다. 0 ~ 100 사이의 값이 표시되며, 0에 가까울 시 CPU가 거의 사용되지 않는다는 걸 의미하고, 100에 가까울 시 CPU가 많이 사용된다는 걸 의미합니다.
* **Ram Usage** : 메모리 사용량을 표시합니다.
* **Network** : 네트워크 업로드/다운로드를 표시합니다. 그래프에는 업로드 값으로 표시됩니다.

<br>

## 명령어
|명령어|퍼미션|기본값|설명|
|-|-|-|-|
|`/작업관리자 off`|smonitor.command.monitor|OP|모니터링을 멈춥니다.|
|`/작업관리자 <tps/load/ram usage/network>`|smonitor.command.monitor|OP|모니터링을 시작합니다.|
