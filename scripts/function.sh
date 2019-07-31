#!/bin/bash
function _session() {
	[ ! -f ${session_file} ] && touch ${session_file}
	bearer=$( cat ${session_file} )
	status_code=$( curl --output /dev/null --silent --write-out "%{http_code}\n" --insecure --header "Authorization: Bearer ${bearer}" https://localhost/ovirt-engine/api )
	if [ ${status_code} -eq 401 ]; then
		session=$( curl \
			   --insecure \
			   --silent \
			   --header "Content-Type: application/x-www-form-urlencoded" \
			   --header "Accept: application/json" \
			   --data "grant_type=password&scope=ovirt-app-api&username=${user}&password=${password}" \
			   https://localhost/ovirt-engine/sso/oauth/token | sed 's/.*"access_token":"\([^"]*\)".*/\1/g' )
		echo ${session} > ${session_file}
	fi
}
function _api() {
	_session
	bearer=$( cat ${session_file} )
	curl \
	--insecure \
	--header "Accept: application/xml" \
	--header "Authorization: Bearer ${bearer}" \
	--header "Prefer: persistent-auth" \
	"${url}/${1}" 2>/dev/null > "${2}"
}

function _xpath() {
	xmllint --xpath "${1}" ${2}
}

function _psql() {
	/opt/rh/rh-postgresql10/root/usr/bin/psql -U gocloud -At -F " " -c "${1}" 
}

function _create_vm() {
	_session
	bearer=$( cat ${session_file} )
	curl \
        --silent \
	--insecure \
	--request POST \
	--header "Version: 4" \
	--header "Accept: application/xml" \
	--header "Content-Type: application/xml" \
	--header "Authorization: Bearer ${bearer}" \
	--header "Prefer: persistent-auth" \
	--data "${1}" \
	"${url}/vms"
}

function _create_image() {
	_session
        bearer=$( cat ${session_file} )
        curl \
        --silent \
        --insecure \
        --request POST \
        --header "Version: 4" \
        --header "Accept: application/xml" \
        --header "Content-Type: application/xml" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        --data "${1}" \
        "${url}/vms/${2}/diskattachments"
}

function _get_disk() {
	_session
        bearer=$( cat ${session_file} )
        curl \
        --silent \
        --insecure \
        --header "Version: 4" \
        --header "Accept: application/xml" \
        --header "Content-Type: application/xml" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        "${url}/disks/${1}"
}

function _create_vnic() {
	_session
        bearer=$( cat ${session_file} )
        curl \
        --silent \
        --insecure \
        --request POST \
        --header "Version: 4" \
        --header "Accept: application/xml" \
        --header "Content-Type: application/xml" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        --data "${1}" \
        "${url}/vms/${2}/nics"
}

function _remove_vm() {
	_session
        bearer=$( cat ${session_file} )
        curl \
        --silent \
        --insecure \
        --request DELETE \
        --header "Version: 4" \
        --header "Accept: application/xml" \
        --header "Content-Type: application/xml" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        --data " \
<action> 
  <force>true</force> 
</action>" \
        "${url}/vms/${1}"
}

function _vm_start() {
        _session
        bearer=$( cat ${session_file} )
        curl \
        --silent \
        --insecure \
        --request POST \
        --header "Version: 4" \
        --header "Accept: application/xml" \
        --header "Content-Type: application/xml" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        --data "<action/>" \
        "${url}/vms/${1}/start"
}

function _vm_start_boot() {
	_session
        bearer=$( cat ${session_file} )
        curl \
        --silent \
        --insecure \
        --request POST \
        --header "Version: 4" \
        --header "Accept: application/xml" \
        --header "Content-Type: application/xml" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        --data " \
<action>
  <vm>
    <os>
      <boot>
        <devices>
          ${1}
        </devices>
      </boot>
    </os>
  </vm>
</action>" \
        "${url}/vms/${2}/start"
}

function _vm_stop() {
	_session
        bearer=$( cat ${session_file} )
        curl \
        --silent \
        --insecure \
        --request POST \
        --header "Version: 4" \
        --header "Accept: application/xml" \
        --header "Content-Type: application/xml" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        --data " \
<action/>" \
        "${url}/vms/${1}/stop"
}

function _vm_reboot() {
	_session
        bearer=$( cat ${session_file} )
        curl \
        --silent \
        --insecure \
        --request POST \
        --header "Version: 4" \
        --header "Accept: application/xml" \
        --header "Content-Type: application/xml" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        --data " \
<action/>" \
        "${url}/vms/${1}/reboot"
}

function _vm_console() {
	_session
        bearer=$( cat ${session_file} )
        curl \
        --silent \
        --insecure \
        --header "Version: 4" \
        --header "Accept: application/x-virt-viewer" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        "${url}/vms/${1}/graphicsconsoles/7370696365"
}


function _vm_status() {
	_session
        bearer=$( cat ${session_file} )
        vm_xml=$( curl \
        --silent \
        --insecure \
        --header "Version: 4" \
        --header "Accept: application/xml" \
        --header "Content-Type: application/xml" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        "${url}/vms/${1}" )

	vm_status=$( xmllint --xpath "/vm/status/text()" - <<< ${vm_xml} )
	echo ${vm_status}
}


function _attach_iso() {
	_session
        bearer=$( cat ${session_file} )
        curl \
        --silent \
        --insecure \
        --request PUT \
        --header "Version: 4" \
        --header "Accept: application/xml" \
        --header "Content-Type: application/xml" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        --data " \
<cdrom>
  <file id='${1}'/>
</cdrom>" \
        "${url}/vms/${2}/cdroms/00000000-0000-0000-0000-000000000000?current=true"
}

function _get_iso() {
	_session
        bearer=$( cat ${session_file} )
        storagedomain_xml=$( curl \
        	--silent \
        	--insecure \
        	--header "Version: 4" \
        	--header "Accept: application/xml" \
        	--header "Content-Type: application/xml" \
        	--header "Authorization: Bearer ${bearer}" \
        	--header "Prefer: persistent-auth" \
        	"${url}/storagedomains" )
	iso_id=$( xmllint --xpath "//storage_domain[type[text()='iso']]/@id" - <<< ${storagedomain_xml} | sed 's/ id="\([^"]*\)"/\1/g' )
        iso_xml=$( curl \
        --silent \
        --insecure \
        --header "Version: 4" \
        --header "Accept: application/json" \
        --header "Content-Type: application/json" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        "${url}/storagedomains/${iso_id}/files" )
	echo ${iso_xml}
}

function _vm_xml() {
	_session
	bearer=$( cat ${session_file} )
	curl \
	--silent \
	--insecure \
	--header "Version: 4" \
	--header "Accept: application/xml" \
	--header "Content-Type: application/xml" \
	--header "Authorization: Bearer ${bearer}" \
	--header "Prefer: persistent-auth" \
	"${url}/vms/${1}" 
}

function _vm_json() {
	_session
	bearer=$( cat ${session_file} )
	curl \
	--silent \
	--insecure \
	--header "Version: 4" \
	--header "Accept: application/json" \
	--header "Content-Type: application/json" \
	--header "Authorization: Bearer ${bearer}" \
	--header "Prefer: persistent-auth" \
	"${url}/vms/${1}?follow=disk_attachments.disk,nics,host,cdroms" 
}

function _vm_iso() {
	_session
        bearer=$( cat ${session_file} )
        curl \
        --silent \
        --insecure \
        --header "Version: 4" \
        --header "Accept: application/xml" \
        --header "Content-Type: application/xml" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        "${url}/vms/${1}/cdroms/00000000-0000-0000-0000-000000000000?current=true"
}

function _vms_xml {
	_session
	bearer=$( cat ${session_file} )
	curl \
	--silent \
	--insecure \
	--header "Version: 4" \
	--header "Accept: application/json" \
	--header "Content-Type: application/json" \
	--header "Authorization: Bearer ${bearer}" \
	--header "Prefer: persistent-auth" \
	"${url}/vms" 
}

function _template_xml {
        _session
        bearer=$( cat ${session_file} )
        curl \
        --silent \
        --insecure \
        --header "Version: 4" \
        --header "Accept: application/xml" \
        --header "Content-Type: application/xml" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        "${url}/templates/${1}?follow=disk_attachments.disk"
}

function _hosts_xml {
	_session
        bearer=$( cat ${session_file} )
        curl \
        --silent \
        --insecure \
        --header "Version: 4" \
        --header "Accept: application/xml" \
        --header "Content-Type: application/xml" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        "${url}/hosts"
}

function _host_vms {
	_session
        bearer=$( cat ${session_file} )
        curl \
        --silent \
        --insecure \
        --header "Version: 4" \
        --header "Accept: application/xml" \
        --header "Content-Type: application/xml" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
        "${url}/vms?search=host=${1}"
}

function _placement_policy {
	_session
        bearer=$( cat ${session_file} )
        curl \
        --silent \
        --insecure \
        --request PUT \
        --header "Version: 4" \
        --header "Accept: application/xml" \
        --header "Content-Type: application/xml" \
        --header "Authorization: Bearer ${bearer}" \
        --header "Prefer: persistent-auth" \
	--data "
<vm>
  <placement_policy>
    <hosts>
      <host>
        <name>${2}</name>
      </host>
    </hosts>
    <affinity>migratable</affinity>
  </placement_policy>
</vm>
" \
"${url}/vms/${1}"
}
