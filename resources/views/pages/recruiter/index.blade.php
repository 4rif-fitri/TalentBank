@extends('layouts.internship-layouts')

@section('css')
@endsection

@section('content')
@endsection

@section('script')
<script>
    let mydata;

    function getMyData() {
        let url = "{{ route('profile.getProfileDataByProfileId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", "{{ session('user_profile_id') }}");

        return $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            success: function ({
                data
            }) {
                console.log("MY DATA", data);
                mydata = data;
            },
            error: function (xhr) {
                console.error(xhr);
            }
        });
    }

    function getPositionsFormMine() {
        let orgid = mydata.organization_users[0].organization.id;

        let url = "{{ route('positions.getPositionsByOrgId', ['id' => '__ID__']) }}";
        url = url.replace("__ID__", orgid);

        return $.ajax({
            url: url,
            type: "GET",
            dataType: "json",
            success: function ({
                data
            }) {
                console.log("POSITIONS DATA", data);
            },
            error: function (xhr) {
                console.error(xhr);
            }
        });
    }

    $(document).ready(async function () {
        await getMyData();
        console.log("mydata siap diambil:", mydata);

        await getPositionsFormMine();
    });
</script>
@endsection