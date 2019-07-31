#!/bin/bash
# Directory Path
resources_dir="/dev/shm/ovirt-cache/"
session_file="${resources_dir}/session.txt"
storage_path="/gocloud_fs"
storage_nfs="172.26.0.253:${storage_path}"

# Command Path
psql="/opt/rh/rh-postgresql10/root/usr/bin/psql -U gocloud"

# Ovirt REST API INFO
url="https://localhost/ovirt-engine/api"
user="admin@internal"
password="2727175#356"

[ ! -d ${basedir} ] && mkdir -p ${basedir}
