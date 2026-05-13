<?php

require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/config/database.php';

requireLogin();

$jsonPath = __DIR__ . '/storage/sites.json';

$sites = [];

if (file_exists($jsonPath)) {
    $sites = json_decode(file_get_contents($jsonPath), true);
}

$stmt = $pdo->query("
    SELECT path
    FROM excluded_paths
    WHERE selected = 1
");

$saved = $stmt->fetchAll(PDO::FETCH_COLUMN);

?>

<!DOCTYPE html>
<html>
<head>

    <title>Backup Excludes Manager</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.tailwindcss.com"></script>
    

</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Topbar -->
    <div class="bg-[#1f2937] text-white shadow">

        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <div>
                <h1 class="text-xl font-semibold">
                    Backup Excludes Manager
                </h1>

                <p class="text-sm text-gray-300">
                    Manage CloudPanel backup exclude directories
                </p>
            </div>

            <div class="flex items-center gap-4">

                <a
                    href="/export.php"
                    class="bg-green-600 hover:bg-green-700 transition px-4 py-2 rounded text-sm"
                >
                    Download excludes.txt
                </a>

                <a
                    href="/logout.php"
                    class="bg-red-600 hover:bg-red-700 transition px-4 py-2 rounded text-sm"
                >
                    Logout
                </a>

            </div>

        </div>

    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto p-6">

        <div class="bg-white rounded-xl shadow overflow-hidden">

            <div class="px-6 py-4 border-b bg-gray-50">

                <h2 class="text-lg font-semibold text-gray-700">
                    Directory Exclusions
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Select directories to exclude from CloudPanel backups.
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700 w-16">
                                Select
                            </th>

                            <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700">
                                Directory
                            </th>

                            <th class="text-left px-6 py-3 text-sm font-semibold text-gray-700 w-32">
                                Size
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                    <?php

                    $currentDomain = '';

                    foreach ($sites as $site):

                        if ($currentDomain !== $site['domain']):

                            $currentDomain = $site['domain'];

                    ?>

					
						<tr class="bg-gray-200">

							<td class="px-6 py-3">
								<input
									type="checkbox"
									class="domain-checkbox w-4 h-4"
									data-domain="<?= htmlspecialchars($currentDomain) ?>"
								>
							</td>

							<td colspan="2" class="px-6 py-3 font-semibold text-gray-800">
								<?= htmlspecialchars($currentDomain) ?>
							</td>

						</tr>						

                    <?php endif; ?>

                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-6 py-4">

								<input
									type="checkbox"
									class="path-checkbox w-4 h-4"
									data-domain="<?= htmlspecialchars($site['domain']) ?>"
									value="<?= htmlspecialchars($site['path']) ?>"
									<?= in_array($site['path'], $saved) ? 'checked' : '' ?>
								>

                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700 font-mono">
                                <?= htmlspecialchars($site['path']) ?>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?= htmlspecialchars($site['size']) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <!-- Bottom Actions -->
        <div class="mt-6 flex gap-4">

            <button
                id="save-btn"
                class="bg-blue-600 hover:bg-blue-700 transition text-white px-6 py-3 rounded-lg shadow"
            >
                Save Selection
            </button>
            <button
                id="download-btn"
                class="bg-blue-600 hover:bg-blue-700 transition text-white px-6 py-3 rounded-lg shadow"
            >
                Export Excludes
            </button>
        </div>

    </div>

<script>

$('#save-btn').on('click', function () {

    let paths = [];

    $('.path-checkbox:checked').each(function () {
        paths.push($(this).val());
    });

    $.post('/save.php', {
        paths: paths
    }, function () {

        alert('Selections saved successfully.');

    }, 'json');

});
  


$('#download-btn').on('click', function () {

    window.location.href = '/export.php';

});  

$(document).on('change', '.domain-checkbox', function () {

    let domain = $(this).data('domain');
    let checked = $(this).is(':checked');

    $('.path-checkbox[data-domain="' + domain + '"]')
        .prop('checked', checked);

});

$('.domain-checkbox').each(function () {

    let domain = $(this).data('domain');

    let total = $('.path-checkbox[data-domain="' + domain + '"]').length;

    let checked = $('.path-checkbox[data-domain="' + domain + '"]:checked').length;

    $(this).prop('checked', total === checked);

});

</script>

<!-- Developer Attribution -->
<div class="text-center mt-6 text-sm text-gray-600">
    <a href="https://raheelshan.com" target="_blank" class="text-gray-600 hover:text-blue-600">Developed by Raheel Shan</a>
</div>

</body>


</html>