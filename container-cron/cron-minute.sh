echo "cron minute"

# hack to get the current directory
# independent of where the script is called from (docker or local)
curdir=`dirname $0`

# find every minute/* and run it
tasks=$(ls $curdir/minute/*)
for task in $tasks
do
    # cat $task
    # check extension and run it
    if [ ${task: -3} == ".sh" ]; then
        echo "-- running sh $task"
        /bin/sh $task
    fi
    if [ ${task: -4} == ".php" ]; then
        echo
        echo "-- running php $task"
        echo
        php $task
        echo
    fi
done
