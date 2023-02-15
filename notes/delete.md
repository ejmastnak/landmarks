About: implement destroy method to delete Landmarks from database.

In controller define

```php
<?php
public function destroy(Landmark $landmark)
{
    $landmark->delete();
    return redirect(route('landmarks.index'));
}
```

The link from Vue is

```vue
<a :href="route('landmarks.destroy', landmark.id)" method="delete">
  Delete
</a>
```
