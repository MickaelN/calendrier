<?php

/**
 * Je dois déterminer : 
 *  - Le nombre de jour dans le mois selectionné
 *  - Le premier jour du mois
 */
//Création du tableau $monthList avec les mois de l'année
$monthList = [
    '01' => 'Janvier',
    '02' => 'Février',
    '03' => 'Mars',
    '04' => 'Avril',
    '05' => 'Mai',
    '06' => 'Juin',
    '07' => 'Juillet',
    '08' => 'Août',
    '09' => 'Septembre',
    '10' => 'Octobre',
    '11' => 'Novembre',
    '12' => 'Décembre'
];

//définition des constantes de début et de fin pour les années
define('START_YEAR', 1980);
define('END_YEAR', 2098);

//On vérifie que le formulaire a bien été selectionné
if (isset($_POST['calendarShow'])) {
    //Création du tableau d'erreur
    $errorList = [];
    //On vérifie que le mois a bien été renseigné
    if (!empty($_POST['month'])) {
        //on vérifie que la saisie est bien une clé du tableau $monthList
        if (array_key_exists($_POST['month'], $monthList)) {
            //On stocke le mois dans une variable
            $monthSelected = $_POST['month'];
        } else {
            //Si la clé n'existe pas, on ajoute une erreur
            $errorList['month'] = 'Le mois renseigné n\'est pas valide.';
        }
    } else {
        $errorList['month'] = 'Veuillez renseigner un mois.';
    }

    //On vérifie que l'année a bien été renseignée
    if (!empty($_POST['year'])) {
        //On vérifie que l'année est bien entre les bornes définies
        if ($_POST['year'] >= START_YEAR && $_POST['year'] <= END_YEAR) {
            //On stocke l'année dans une variable
            $yearSelected = $_POST['year'];
        } else {
            //Si l'année n'est pas dans les bornes, on ajoute une erreur
            $errorList['year'] = 'L\'année renseignée n\'est pas valide.';
        }
    } else {
        $errorList['year'] = 'Veuillez renseigner une année.';
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Calendrier</title>
</head>

<body>
    <form action="" method="POST" id="form">
        <label for="month">Mois</label>
        <select name="month" id="month">
            <option value="00" disabled <?= isset($monthSelected) ? '' : 'selected' ?>>Mois</option>
            <?php
            //Boucle pour afficher les mois
            foreach ($monthList as $monthNumber => $monthName) { ?>
                <option value="<?= $monthNumber ?>" <?= (isset($monthSelected) && $monthSelected == $monthNumber) ? 'selected' : '' ?>><?= $monthName ?></option>
            <?php } ?>
        </select>
        <p><small><?= isset($errorList['month']) ? $errorList['month'] : '' ?></small></p>
        <label for="year">Année</label>
        <select name="year" id="year">
            <option value="00" disabled <?= isset($yearSelected) ? '' : 'selected' ?> >Année</option>
            <?php
            //Boucle pour afficher les années
            for ($year = START_YEAR; $year <= END_YEAR; $year++) { ?>
                <option value="<?= $year ?>" <?= (isset($yearSelected) && $yearSelected == $year) ? 'selected' : '' ?> ><?= $year ?></option>
            <?php } ?>
        </select>
        <p><small><?= isset($errorList['year']) ? $errorList['year'] : '' ?></small></p>
        <input type="submit" value="Afficher" name="calendarShow" id="calendarShow">
    </form>
    <!-- Si je n'ai pas d'erreur dans le formulaire, je peux afficher le calendrier -->
    <?php
    if (isset($errorList) && count($errorList) === 0) {
        //Nombre de jour dans le mois
        $totalDayinMonth = cal_days_in_month(CAL_GREGORIAN, $monthSelected, $yearSelected);
        //Premier jour du mois. 1 pour Lundi, 2 pour Mardi, etc.
        $firstDayInMonth = date('N', mktime(0, 0, 0, $monthSelected, 1, $yearSelected));
        $dayInMonth = 1;
    ?>
        <p>Le premier jour du mois est : <?= $firstDayInMonth ?></p>
        <table>
            <thead>
                <tr>
                    <th>Lundi</th>
                    <th>Mardi</th>
                    <th>Mercredi</th>
                    <th>Jeudi</th>
                    <th>Vendredi</th>
                    <th>Samedi</th>
                    <th>Dimanche</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $nbWeek = 1;
                $begin = microtime(true);
                while ($dayInMonth < $totalDayinMonth) {
                ?>
                    <tr>
                        <?php
                        for ($dayInWeek = 1; $dayInWeek <= 7; $dayInWeek++) {
                            if ($dayInMonth <= $totalDayinMonth) {
                                //Si c'est la première semaine et que le mois n'a pas encore commencé alors j'affiche une case vide
                                if (($dayInWeek < $firstDayInMonth) && ($nbWeek == 1)) { ?>
                                    <td class="empty"></td>
                                <?php } else {
                                ?>
                                    <td><?= $dayInMonth++ ?></td>
                                <?php
                                }
                            } else { ?>
                                <td class="empty"></td>
                        <?php
                            }
                        }
                        ?>
                    </tr>
                <?php
                    $nbWeek++;
                } 
                $end = microtime(true);
                ?>
            </tbody>
        </table>
        <p>Cela a mis : <?= $end - $begin ?></p>
    <?php
    } ?>

</body>

</html>