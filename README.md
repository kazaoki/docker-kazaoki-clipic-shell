
NAME
====

docker-kazaoki-clipic-shell - Dockerイメージのkazaoki/clipicを操作するシェルスクリプト

SYNOPSIS
--------

	# picture to char by ANSI 256 colors. (use docker image:kazaoki/clipic)
	clipic cat.jpg
	clipic http://hogehoge.hoge/dog.png

DESCRIPTION
-----------
これはDockerイメージ`kazaoki/clipic`を楽チンに操作するためのシェルです。
`kazaoki/clipic`は、標準入力から渡された画像バイナリデータ、もしくは画像URLのテキストデータを、Linuxコマンドライン上にてANSI256色の文字キャラクタを使用して再現するものです。
コマンドライン上で何の画像かと確認したい際に便利かと思います。（モザイクみたいになるので、細かい部分は確認できませんが）

INSTALL
-------

`/usr/bin/`とかPATHが通ってるとこにダウンロードして実行権を与えるだけでいいです。
```
$ curl -o clipic -L https://raw.githubusercontent.com/kazaoki/docker-kazaoki-clipic-shell/master/clipic.sh
$ chmod +x clipic
$ mv clipic /usr/bin/
```

`kazaoki/clipic`イメージは`clipic.sh`実行時に[Docker Hub](https://registry.hub.docker.com/)から自動的にpullされますので、docker環境とbashとかが入っていれば特に他には必要ないです。多分。

RUN
---

	clipic (ファイル名か画像URL) (横幅)

基本的にこれだけです。
横幅の指定をしない場合はコンソールの横幅になります。（コンソールの横幅も取得できない場合はデフォルトで50となります）


サンプルイメージ
----------------

（そのうち）


FAQ
---
#### どんな画像でもOKです？
jpg,png,gifとか。

#### シェルの環境に条件はありますか？
ちょっとよくわかりません。  
bashで試してますので、zshとかcshとか言われると困ります。


BUG REPORTING
-------------
[GitHub Issue](https://github.com/kazaoki/docker-kazaoki-clipic-shell/issues)に書けるのであればそこへ。


AUTHOR
------
[カザオキラボ - http://kazaoki.jp](http://kazaoki.jp)


LICENSE
-------
特には。  
`clipic.sh`や`Dockerfile`等はご自由にいじってどうぞ。
