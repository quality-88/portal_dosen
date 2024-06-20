// Assume you already have the necessary setup for the environment, including the 'mssql' library.
const sql = require('mssql');

// Database configuration
const config = {
  user: 'sa',
  password: 'biasa',
  server: '.',
  database: 'qualitydb'
};



console.log("processForm.js loaded");

async function processForm() {
    try {
        const nama = document.getElementById('NamaDosen').value || '';
        const TA = document.getElementById('TA').value || '';
        const Semester = document.getElementById('Semester').value || '';
        const startDate = document.getElementById('date').value || '';
        const endDate = document.getElementById('endDate').value || '';
        const idKampus = document.getElementById('idkampus').value || '';
        const prodiId = document.getElementById('prodi').value;

        const fakultasDataResult = await sql.query`
            SELECT prodi.idfakultas, fakultas.fakultas, prodi.prodi
            FROM prodi
            JOIN fakultas ON prodi.idfakultas = fakultas.idfakultas
            WHERE prodi.idfakultas = ${prodiId}
        `;

        if (!fakultasDataResult.recordset.length) {
            console.error('Fakultas data not found');
            return;
        }

        const idFakultas = fakultasDataResult.recordset[0].idfakultas;
        const fakultas = fakultasDataResult.recordset[0].fakultas;
        const prodi = fakultasDataResult.recordset[0].prodi;

        const whereClause = {
            TA,
            Semester,
            startDate,
            endDate,
            idKampus,
        };

        if (nama !== '') {
            whereClause.NamaDosen = nama;
        }

        const results = await sql.query`
            SELECT
                a.fingerin as tglin,
                a.lokasi as lokasi,
                a.prodi as prodi,
                a.idmk as idmk,
                a.matakuliah as matakuliah,
                a.sks as sks,
                a.masuk as masuk,
                a.keluar as keluar,
                a.kelas as kelas,
                a.jumlah as jumlah,
                a.pertemuanke,
                CASE
                    WHEN (MAX(a.pertemuanke) IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16)
                        AND a.jumlah BETWEEN MAX(b.jumlahMHSAwalA) AND MAX(b.JumlahMHSAkhirA)) THEN 0
                    WHEN (MAX(a.pertemuanke) IN (6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16)
                        AND a.jumlah BETWEEN MAX(b.JumlahMhsAwalB) AND MAX(b.JumlahMHSAkhirB)) THEN 0
                    WHEN (MAX(a.pertemuanke) IN (9,10,11,12,13,14,15,16)
                        AND a.jumlah BETWEEN MAX(b.jumlahMHSAWALC) AND MAX(B.JumlahMHSAkhirC)) THEN 0
                    ELSE MAX(a.TotalHonor) 
                END AS honorSKSDosen,
                a.namadosen as namadosen,
                ISNULL(MAX(a.keterangan), '') as keterangan
            FROM
                TblSementaraHonorDosen a
            INNER JOIN
                TblKoutaHonor b ON b.TA = a.TA AND b.Semester = a.semester
            WHERE
                a.NamaDosen = ${nama}
                AND a.TA = ${TA}
                AND a.Semester = ${Semester}
                AND a.fingerin >= ${startDate}
                AND a.fingerin <= ${endDate}
                AND a.idkampus = ${idKampus}
            GROUP BY
                a.fingerin,
                a.lokasi,
                a.prodi,
                a.idmk,
                a.matakuliah,
                a.sks,
                a.masuk,
                a.keluar,
                a.kelas,
                a.jumlah,
                a.namadosen,
                a.pertemuanke,
                ISNULL(a.keterangan, '')
            ORDER BY
                a.matakuliah ASC,
                a.fingerin ASC
        `;

        const totalHonor = results.recordset.length
            ? results.recordset.reduce((acc, curr) => acc + curr.honorSKSDosen, 0)
            : 0;

        // Process the results as needed
        console.log('Results:', results.recordset);
        console.log('Total Honor:', totalHonor);
        console.log('idFakultas:', idFakultas);
        console.log('Fakultas:', fakultas);
        console.log('Prodi:', prodi);

        // Display results in all_type.blade
        displayResults(results.recordset, totalHonor, idFakultas, fakultas, prodi, nama);
    } catch (error) {
        console.error('Error processing the form:', error);
    }
}

function displayResults(results, totalHonor, idFakultas, fakultas, prodi, nama) {
    // Customize this part based on how you want to display the results in all_type.blade
    console.log('Displaying results:', results);
    console.log('Total Honor:', totalHonor);
    console.log('ID Fakultas:', idFakultas);
    console.log('Fakultas:', fakultas);
    console.log('Prodi:', prodi);
    console.log('Nama Dosen:', nama);

    // Example: Update HTML elements with the results
    document.getElementById('resultContainer').innerHTML = `
        <h2>Data Table</h2>
        <!-- Add your HTML structure here using the fetched data -->
    `;
}

// Call the processForm function when the form is submitted








