#!/usr/bin/env bash

set -e -u

# base dir is the TYPO3 project path
BASEDIR=__BASE_DIR__

# The key under which the script was registered
SCRIPTKEY=__SCRIPT_KEY__

DATADIR="${BASEDIR}/var/run/tx_runscript"
PIDFILE="${DATADIR}/${SCRIPTKEY}.pid"
STATUSFILE="${DATADIR}/${SCRIPTKEY}.stat"

ENO_SUCCESS=0
ENO_GENERAL=1
ENO_LOCKFAIL=2
ENO_RECVSIG=3

cd $BASEDIR
mkdir -p ${DATADIR}

echo 0  > ${STATUSFILE}

trap 'ECODE=$?; echo "$ECODE" > $STATUSFILE' 0

# We use a pid in a hardcoded file as a lock
if [[ ! -f "${PIDFILE}" ]]; then

    trap 'ECODE=$?; rm -rf "${PIDFILE}"' 0

    # No lock, create one
    echo "$$" >"${PIDFILE}"
    echo "0" >"${STATUSFILE}"

    trap 'echo ${ENO_RECVSIG} > $STATUSFILE' 1 2 3 15

    # Pass environemnt variables
    __VARS__

    # Run the script
    __SCRIPT__

else

    OTHERPID="$(cat "${PIDFILE}")"

    if [[ $? != 0 ]]; then
        # Lock failed, other is still active
        echo ${ENO_LOCKFAIL} > ${STATUSFILE}
        exit ${ENO_LOCKFAIL}
    fi

    CHECK_RUNNING="$(kill -0 ${OTHERPID})"

    if [[ "${CHECK_RUNNING}" != "" ]]; then
        # Remove stale lock and try again
        rm -rf "${PIDFILE}"
        exec "$0" "$@"
    else
        # Lock failed, other is still actvie
        echo ${ENO_LOCKFAIL} > ${STATUSFILE}
        exit ${ENO_LOCKFAIL}
    fi

fi
