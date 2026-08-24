export function educationTemplate(programme){
    return ` <div class="alert alert-primary d-flex gap-2" role="button"
                  data-programme-id="${programme.id}">

                <div class="bg-body d-flex justify-content-center align-items-center"
                     style="width: 40px; height: 40px; border-radius: 50%;">
                    <i class="fa-solid fa-graduation-cap"></i>
            </div>

            <div>
                <p>${programme.organization?.company_name ?? 'Unknown Institution'}</p>
                <p>${programme.programme_name ?? ''}</p>
                <p>${programme.programme_level ?? ''}</p>
                <p>${programme.duration_years ?? ''} Years</p>
            </div>
        </div>`;
}
