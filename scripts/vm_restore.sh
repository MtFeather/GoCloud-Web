#!/bin/bash
source ./config.sh
source ./function.sh
vm_id="${1}"

check=$( mount | grep "${storage_path}" )
if [ "${check}" == "" ]; then
        [ ! -d ${storage_path} ] && mkdir ${storage_path}
        mount -t nfs ${storage_nfs} ${storage_path}
fi

result=( `_psql "SELECT template_id,disk_id,image_id FROM student_vms WHERE vm_id = '${vm_id}';"` )
template_id=${result[0]}
disk_id=${result[1]}
image_id=${result[2]}

template_xml=$( _template_xml "${template_id}" )
temp_disk_id=$( xmllint --xpath "//template/disk_attachments/disk_attachment/disk/@id" - <<< ${template_xml} | sed 's/ id="\([^"]*\)"/\1/g' )
temp_image_id=$( xmllint --xpath "//template/disk_attachments/disk_attachment/disk/image_id/text()" - <<< ${template_xml} )
storagedomain=$( xmllint --xpath "//template/disk_attachments/disk_attachment/disk/storage_domains/storage_domain/@id" - <<< ${template_xml} | sed 's/ id="\([^"]*\)"/\1/g' )

qemu-img create -f qcow2 -b ../${temp_disk_id}/${temp_image_id} ${storage_path}/ovirt_data/${storagedomain}/images/${disk_id}/${image_id} &> /dev/null

echo "ok"
