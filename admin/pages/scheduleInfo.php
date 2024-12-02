<?php
    if(isset($_GET["scheduleId"]) && !empty(trim($_GET["scheduleId"]))){
        $sql = "SELECT * FROM schedulestbl WHERE id = ?";

        if($stmt = mysqli_prepare($conn, $sql)){

            mysqli_stmt_bind_param($stmt, "i", $param_id);
            $param_id = trim($_GET["scheduleId"]);

            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                } else{
                    header("location: index.php?page=schedules");
                    exit();
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        mysqli_stmt_close($stmt);
    } else{
        header("location: index.php?page=schedules");
        exit();
    }
?>

<div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/ceres/admin" style="font-family: 'Times New Roman', serif;"><b>DASHBOARD</b></a></li>
            <li class="breadcrumb-item active" aria-current="page" style="font-family: 'Times New Roman', serif;"><b>SCHEDULES</b></li>
        <?php
                $routeId = $row["routeId"];
                $routeResult = mysqli_query($conn,"SELECT * FROM routes WHERE id = '$routeId'");
                while($routeRow = mysqli_fetch_array($routeResult)) {
            ?>

            <li class="breadcrumb-item active" aria-current="page">
                <?php echo $routeRow["routeFrom"]." - ".$routeRow["routeTo"]; ?>
            </li>

            <?php
                }
            ?>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <p><b><?php echo $row["driverId"]; ?></b></p>
        </div>
        <div class="col-md-4">
            <div id="accordion">
                <div class="card mb-2">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#setPlan"
                                aria-expanded="true" aria-controls="setPlan">
                                Set Plan
                            </button>
                        </h5>
                    </div>

                    <div id="setPlan" class="collapse" aria-labelledby="headingSetPlan" data-parent="#accordion">
                        <div class="card-body">
                            set plan goes here..
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#route"
                                aria-expanded="false" aria-controls="route">
                                Route Info.
                            </button>
                        </h5>
                    </div>
                    <div id="route" class="collapse" aria-labelledby="headingRoute" data-parent="#accordion">
                        <div class="card-body">
                            <?php
                                $routeId = $row["routeId"];
                                $bResult = mysqli_query($conn,"SELECT * FROM routes WHERE id = '$routeId'");
                                while($bRow = mysqli_fetch_array($bResult)) {
                            ?>

                            <p class="mb-0">
                                <span class="small text-muted">Location: </span>
                                <span
                                    class="text-uppercase"><?php echo $bRow["routeFrom"].' - '.$bRow["routeTo"]; ?></span>
                            </p>
                            <p class="mb-0">
                                <span class="small text-muted">Fare: </span>
                                <span class="text-uppercase"><?php echo $bRow["fare"]; ?></span>
                            </p>

                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#bus"
                                aria-expanded="false" aria-controls="bus">
                                Bus Info.
                            </button>
                        </h5>
                    </div>
                    <div id="bus" class="collapse" aria-labelledby="headingBus" data-parent="#accordion">
                        <div class="card-body">
                            <?php
                                $busId = $row["busId"];
                                $bResult = mysqli_query($conn,"SELECT * FROM bustbl WHERE id = '$busId'");
                                while($bRow = mysqli_fetch_array($bResult)) {
                            ?>

                            <p class="mb-0">
                                <span class="small text-muted">Bus #: </span>
                                <span class="text-uppercase"><?php echo $bRow["busNum"]; ?></span>
                            </p>

                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#driver"
                                aria-expanded="false" aria-controls="driver">
                                Driver Info.
                            </button>
                        </h5>
                    </div>
                    <div id="driver" class="collapse" aria-labelledby="headingDriver" data-parent="#accordion">
                        <div class="card-body">
                            <?php
                                $driverId = $row["driverId"];
                                $driverResult = mysqli_query($conn,"SELECT * FROM drivertbl WHERE id = '$driverId'");
                                while($driverRow = mysqli_fetch_array($driverResult)) {
                            ?>

                            <p class="mb-0">
                                <span class="small text-muted">Name: </span>
                                <span class="text-uppercase"><?php echo $driverRow["name"]; ?></span>
                            </p>

                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#conductor"
                                aria-expanded="false" aria-controls="conductor">
                                Conductor Info.
                            </button>
                        </h5>
                    </div>
                    <div id="conductor" class="collapse" aria-labelledby="headingConductor" data-parent="#accordion">
                        <div class="card-body">
                            <?php
                                $conductorId = $row["conductorId"];
                                $cResult = mysqli_query($conn,"SELECT * FROM conductortbl WHERE id = '$conductorId'");
                                while($cRow = mysqli_fetch_array($cResult)) {
                            ?>

                            <p class="mb-0">
                                <span class="small text-muted">Name: </span>
                                <span class="text-uppercase"><?php echo $cRow["name"]; ?></span>
                            </p>

                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>