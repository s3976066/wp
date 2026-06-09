#!/usr/bin/env bash
# deploy.sh — 把某个项目文件夹推送到 RMIT titan 服务器
# 在 Git Bash 里运行（不要用 PowerShell，Windows 路径写法不同）
#
# 用法:
#   ./deploy.sh a3            # 推送/更新 a3
#   ./deploy.sh myproject     # 推送任何其它项目文件夹
#
# 前提：已配置 SSH 密钥免密登录，否则每次会要求输入一次密码。
#   ssh-keygen -t ed25519
#   cat ~/.ssh/id_ed25519.pub | ssh s3976066@titan.csit.rmit.edu.au \
#     "mkdir -p ~/.ssh && chmod 700 ~/.ssh && cat >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys"

set -euo pipefail
shopt -s dotglob          # 让 * 也包含 .htaccess / .gitignore 等隐藏文件

PROJECT="${1:?用法: ./deploy.sh <项目文件夹名>  例如 ./deploy.sh a3}"

LOCAL_ROOT="/c/xampp/htdocs/wp-repo"      # 本地仓库根目录
USER="s3976066"
HOST="titan.csit.rmit.edu.au"
REMOTE="public_html/wp/$PROJECT"          # 远端目标（相对于 home）

SRC="$LOCAL_ROOT/$PROJECT"
[ -d "$SRC" ] || { echo "找不到本地文件夹: $SRC"; exit 1; }

echo ">> 推送 $SRC"
echo "   ->  $USER@$HOST:~/$REMOTE/"

ssh "$USER@$HOST" "mkdir -p ~/$REMOTE"
scp -r "$SRC"/* "$USER@$HOST:~/$REMOTE/"

echo ">> 完成 ✓  访问: https://$HOST/~$USER/wp/$PROJECT/"
