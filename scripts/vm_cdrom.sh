#!/bin/bash
source ./config.sh
source ./function.sh
vm_id="${1}"
cdrom="${2}"
_attach_iso "$cdrom" "$vm_id"
