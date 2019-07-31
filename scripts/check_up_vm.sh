#!/bin/bash
source ./config.sh
source ./function.sh
student_id=${1}
vm_up_id=""

student_vms=( `_psql "SELECT vm_id FROM student_vms WHERE status = 1 AND student = ${student_id};"` )

for vm_id in "${student_vms[@]}"
do
	vm_status=$( _vm_status "${vm_id}" )
	if [ ${vm_status} != "down" ]; then
		vm_up_id="${vm_id}"
		echo ${vm_up_id}
		break
	fi
done
