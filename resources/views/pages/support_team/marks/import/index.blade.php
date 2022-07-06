@extends('layouts.master')
@section('page_title', 'Import Exam Marks')
@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><i class="icon-books mr-2"></i> Import Exam Marks</h5>
        {!! Qs::getPanelOptions() !!}
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('marks.selector') }}">
            @csrf
            <div class="row">
                <div class="col-md-10">
                    <fieldset>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exam_id" class="col-form-label font-weight-bold">Exam:</label>
                                    <select required id="exam_id" name="exam_id" data-placeholder="Select Exam" class="form-control select">
                                        @foreach($exams as $ex)
                                        <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                                    <select required onchange="getClassSubjects(this.value)" id="my_class_id" name="my_class_id" class="form-control select">
                                        <option value="">Select Class</option>
                                        @foreach($my_classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file_uploader" class="col-form label font-weight-bold">Upload File:</label>
                                    <input type="file" id="file_uploader" class="form-control form-input-styled">
                                </div>
                            </div>

                        </div>

                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="table-responsive">
                    <table class="table tableforContact" id="contactinformation">
                        <thead>
                            <tr id="header_row">
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </form>
        <div class="fixed-bottom text-right  ">
            <button class="btn bg-success mx-3 text-white" onclick="saveData()">Store Records</button>
        </div>
    </div>
</div>
<script>
    $("#file_uploader").change(function() {

        console.log('changed')

        var uploader = $("#file_uploader");
        var data = new FormData()
        data.append('file', uploader[0].files[0]);
        data.append('_token', $('meta[name="csrf-token"]').attr('content'))

        $.ajax({
            processData: false,
            contentType: false,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: data,
            url: '{{ route('marks.upload_excel')}}',
            success: function(data) {

                var big = Object.keys(data).reduce((a, b) => data[a] > data[b] ? a : b);
                var nextBig = nextBiggest(Object.keys(data));

                makeTableFromExcel(data[big], data[nextBig])
            }

        })
    })

    function makeTableFromExcel(data, heading) {
        const table = document.querySelector("#contactinformation");
        const headers = table.querySelector("thead tr");
        const body = table.querySelector("tbody");
        // console.log(heading)  

        // Create headers
        var count = table.rows.length;
        for (var i = 0; i < count; i++) {
            // console.log(key)
            var object = Object.values(heading[i]);
            for (var j = 0; j < object.length; j++) {
                const header = document.createElement("th");
                header.innerText = object[j];
                headers.append(header);
            }

        }

        // Create tbody rows
        data.forEach(obj => {
            delete obj[0]
            const row = document.createElement("tr");
            body.append(row);
            // Create row element
            for (const key in obj) {
                const value = document.createElement("td");

                value.innerText = obj[key];
                row.append(value);
            }
        });
    }

    function nextBiggest(arr) {
        let max = -Infinity,
            result = -Infinity;

        for (const value of arr) {
            const nr = Number(value)

            if (nr > max) {
                [result, max] = [max, nr] // save previous max
            } else if (nr < max && nr > result) {
                result = nr; // new second biggest
            }
        }

        return result;
    }

    function saveData() {
        var table = document.getElementById('contactinformation');
        var count = table.rows.length;
        var i;
        var headers = table.rows[0].cells;
        var targeted = {
            Pupil: '-',
            Math: '-',
            MathGrade: '-',
            English: '-',
            EnglishGrade: '-',
            Kiswahili: '-',
            KiswahiliGrade: '-',
            Science: '-',
            ScienceGrade: '-',
            SST: '-',
            SSTGrade: '-',
            CME: '-',
            CMEGrade: '-',
            Total: '-',
            Average: '-',
            Grade: '-',
            Position: '-',
            Phone: '-'
        };

        var data = new Array();

        for(i=1; i<headers.length; i++){
              if(headers[i].innerText.localeCompare('PUPILS\'NAME') == 0){
                  targeted.Pupil = i;
              }
              if(headers[i].innerText.localeCompare('MATH') == 0){
                  targeted.Math = i;
              }
              if(headers[i].innerText.localeCompare('MATH_GRADE') == 0){
                  targeted.MathGrade = i;
              } 
              if(headers[i].innerText.localeCompare('ENGLISH') == 0){
                  targeted.English = i;
              }
              if(headers[i].innerText.localeCompare('ENGLISH_GRADE') == 0){
                  targeted.EnglishGrade = i;
              }
              if(headers[i].innerText.localeCompare('KISWAHILI') == 0){
                  targeted.Kiswahili = i;
              }
              if(headers[i].innerText.localeCompare('KISWAHILI_GRADE') == 0){
                  targeted.KiswahiliGrade = i;
              }
              if(headers[i].innerText.localeCompare('SCIE&TECHNOLOGY') == 0){
                  targeted.Science = i;
              }
              if(headers[i].innerText.localeCompare('SCIENCE_GRADE') == 0){
                  targeted.ScienceGrade = i;
              }
              if(headers[i].innerText.localeCompare('SST') == 0){
                  targeted.SST = i;
              }
              if(headers[i].innerText.localeCompare('SST_GRADE') == 0){
                  targeted.SSTGrade = i;
              }
              if(headers[i].innerText.localeCompare('CME') == 0){
                  targeted.CME = i;
              }
              if(headers[i].innerText.localeCompare('CME_GRADE') == 0){
                  targeted.CMEGrade = i;
              }
              if(headers[i].innerText.localeCompare('POSITION') == 0){
                  targeted.Position = i;
              }
              if(headers[i].innerText.localeCompare('PHONE') == 0){
                  targeted.Phone = i;
              }
              
          }

          i=0;
          var current = {user: '-', time: '-', status: '-'};
          for(i=1; i<count; i++){
              current = {
                Pupil: '-',
                Math: '-',
                MathGrade: '-',
                English: '-',
                EnglishGrade: '-',
                Kiswahili: '-',
                KiswahiliGrade: '-',
                Science: '-',
                ScienceGrade: '-',
                SST: '-',
                SSTGrade: '-',
                CME: '-',
                CMEGrade: '-',
                Total: '-',
                Average: '-',
                Grade: '-',
                Position: '-',
                Phone: '-'
              };

              current.Pupil = table.rows[i].cells[targeted.Pupil].innerText;
              current.Math = table.rows[i].cells[targeted.Math].innerText;
              current.MathGrade = table.rows[i].cells[targeted.MathGrade].innerText;
              current.English = table.rows[i].cells[targeted.English].innerText;
              current.EnglishGrade = table.rows[i].cells[targeted.EnglishGrade].innerText;
              current.Kiswahili = table.rows[i].cells[targeted.Kiswahili].innerText;
              current.KiswahiliGrade = table.rows[i].cells[targeted.KiswahiliGrade].innerText;
              current.Science = table.rows[i].cells[targeted.Science].innerText;
              current.ScienceGrade = table.rows[i].cells[targeted.ScienceGrade].innerText;
              current.SST = table.rows[i].cells[targeted.SST].innerText;
              current.SSTGrade = table.rows[i].cells[targeted.SSTGrade].innerText;
              current.CME = table.rows[i].cells[targeted.CME].innerText;
              current.CMEGrade = table.rows[i].cells[targeted.CMEGrade].innerText;
              current.Position = table.rows[i].cells[targeted.Position].innerText;
              current.Phone = table.rows[i].cells[targeted.Phone].innerText;
              data.push(current);
          }
          
        //   alert(JSON.stringify(data));
        // console.log(data);
          
        var formData =  new FormData();
        formData.append('data',JSON.stringify(data));

        $.ajax({
            processData: false,
            contentType: false,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: formData,
            url: "{{Route('marks.send.sms')}}",
            success: function(data) {
                console.log(data);
            },
            error: function(data) {
                console.log(data);
            }

        })

        //   alert(JSON.stringify(data));
        // alert(xhttp.status);
        // console.log(data);
    }
</script>
<style>

</style>
@endsection