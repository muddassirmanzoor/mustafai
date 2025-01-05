<script>
    //_________for admin pdf ________//
    function dataCustomizetbaleAdmin(title, docWidth, columnsss) {
        // if(language == 'english'){
        return arry = [{
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o" aria-hidden="true" title="Admin Users"></i>&nbsp;Export as PDF',
                title: title,
                sCharSet: 'utf32',
                sBom: true,
                dirrection: 'ltr',
                orientation: 'landscape',
                alignment: "center",
                exportOptions: {
                    columns: columnsss,
                    alignment: "center",
                    orthogonal: "PDF",
                    modifier: {
                        order: 'index',
                        page: 'current'
                    },
                    format: {
                        body: function(data, row, column, node) {
                            const arabic =
                                /[\u0600-\u06FF-\u077F-\uFB50-\uFDFF-\uFE70‌-\uFEFF]/;

                            if (arabic.test(data)) {
                                var splitDataString = data.split(' ');

                                var dataStringNew = '';
                                var inc = 0;

                                var wordsArray = [];

                                $.each(splitDataString.reverse(), function(index, value) {
                                    inc = inc + 1;
                                    dataStringNew = dataStringNew + ' ' + value;

                                    if (inc > 25) {
                                        dataStringNew = dataStringNew + ' \n';
                                        inc = 0;
                                        wordsArray.push(dataStringNew);
                                        dataStringNew = '';
                                    } else if (typeof(splitDataString[index + 1]) == "undefined") {
                                        dataStringNew = dataStringNew + ' \n';
                                        wordsArray.push(dataStringNew);
                                    }
                                });

                                var returnData = '';
                                $.each(wordsArray.reverse(), function(index, value) {
                                    returnData = returnData + value;
                                });

                                return returnData;
                                // return data.split(' ').reverse().join(' ');
                            }
                            return data;
                        },
                        header: function(data, row, column, node) {
                            const arabic =
                                /[\u0600-\u06FF-\u0750-\u077F-\uFB50-\uFDFF-\uFE70‌-\uFEFF]/;

                            if (arabic.test(data)) {
                                return data.split(' ').reverse().join(' ');
                            }
                            return data;
                        }
                    }
                },
                customize: function(doc) {
                    doc.defaultStyle.font = 'Arial';
                    doc.content[1].table.widths = docWidth;
                    doc.styles.tableBodyEven.alignment = "center";
                    doc.styles.tableBodyOdd.alignment = "center";
                    doc.content.splice(1, 0, {
                        margin: [0, -70, 0, 20],
                        alignment: 'right',
                        image: GlobalVar.image_logo,
                    });
                    doc.styles.title = {
                        // color: 'red',
                        fontSize: '45',
                        // margin: [ 120, 0, 0, -120],
                        // background: 'blue',
                        alignment: 'left'
                    }
                }
            },
            {
                extend: 'excelHtml5',
                title: title,
                text: '<i class="fa fa-file-excel-o" aria-hidden="true" title="Filtered User"></i>&nbsp;Export as CSV',
                exportOptions: {
                    columns: columnsss,
                },
            },
            // {
            //     extend: 'pdfHtml5',
            //     title: 'Data export'
            // },
        ];
    }
</script>
