<?php

function success($message)
{
    if (is_string($message))
    {
        $alert = "
            <div id='messages'>
                <div class='alert alert-dismissible alert-success'>
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                    <strong>Success!</strong> <span>$message</span>
                </div>
            </div>";
    }

    if (is_array($message))
    {
        $alert = "<div id='messages'>";
        foreach ($message as $cur_message)
        {
            $alert = $alert . "
                    <div class='alert alert-dismissible alert-danger'>
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                        <strong>Error!</strong> <span>$cur_message</span>
                    </div>";
        }
        $alert = $alert . "</div>";
    }

    return($alert);
}

function failure($message)
{

    if (is_string($message))
    {
        $alert = "
            <div id='messages'>
                <div class='alert alert-dismissible alert-danger'>
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                    <strong>Error!</strong> <span>$message</span>
                </div>
            </div>";
    }

    if (is_array($message))
    {
        $alert = "<div id='messages'>";
        foreach ($message as $cur_message)
        {
            $alert = $alert . "
                    <div class='alert alert-dismissible alert-danger'>
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                        <strong>Error!</strong> <span>$cur_message</span>
                    </div>";
        }
        $alert = $alert . "</div>";
    }

    return($alert);
}

?>