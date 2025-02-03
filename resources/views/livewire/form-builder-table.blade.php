<div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($forms as $form)
                <tr>
                    <td>{{ $form->id }}</td>
                    <td>{{ $form->name }}</td>
                    <td>
                        @include('FormBuilder.table-components.action_button', ['row' => $form])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $forms->links() }} 
</div>
