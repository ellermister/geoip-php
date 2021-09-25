# GEOIP 解析 IP 地址

## 安装 GEOIPUPDATE 应用程序

可以选择使用 apt 包管理安装：

```bash
apt -y update && apt install -y geoipupdate
```

也可以选择直接下载二进制文件解压安装：

```bash
mkdir /usr/local/geoipupdate && cd /usr/local/geoipupdate
wget https://github.com/maxmind/geoipupdate/releases/download/v4.8.0/geoipupdate_4.8.0_linux_amd64.tar.gz
tar -xzf geoipupdate_4.8.0_linux_amd64.tar.gz
mv geoipupdate_4.8.0_linux_amd64/* . && rm -rf geoipupdate_4.8.0_linux_amd64
ln -s /usr/local/geoipupdate/geoipupdate /usr/local/bin/
```



## 更新 GEOIP LITE

记得更新 /usr/local/geoipupdate/GeoIP.conf 或者 /etc/GeoIP.conf 中的 AccountID、LicenseKey 更新

以及将 EditionIDs 的值改为：

```
GeoLite2-Country GeoLite2-City GeoLite2-ASN
```

更新命令：

```bash
geoipupdate -d data
```

或者可以指定配置

```bash
geoipupdate -f /etc/GeoIP.conf -d data
```

