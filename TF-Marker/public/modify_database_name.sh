#!/bin/bash
# 假设将sakila数据库名改为new_sakila
# MyISAM直接更改数据库目录下的文件即可
new_name="TF-Marker"
old_name="TF-Cellmarker"
user=root
password="hmudq?lcq#_>123987Zz"
mysql -u$user -p$password -e "create database if not exists \`$new_name\`"
list_table=$(mysql -u$user -p$password -Nse "select table_name from information_schema.TABLES where TABLE_SCHEMA='$old_name'")

for table in $list_table
do
    mysql -u$user -p$password -e "rename table \`$old_name\`.$table to \`$new_name\`.$table"
done