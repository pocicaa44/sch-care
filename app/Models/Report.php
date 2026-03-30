<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Report extends Model
{
  protected $fillable = [
    'user_id',
    'title',
    'location',
    'description',
    'evidence_path',
    'is_anonymous',
    'status',
    'deleted_by_user_at',
    'deleted_by_admin_at',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function comments()
  {
    return $this->hasMany(Comment::class);
  }

  public function images()
  {
    return $this->hasMany(ReportImage::class);
  }

  // ─── Scopes ──────────────────────────────────────────────
  public function scopeVisibleToUser(Builder $query): Builder
  {
    return $query->whereNull('deleted_by_user_at');
  }

  public function scopeVisibleToAdmin(Builder $query): Builder
  {
    return $query->whereNull('deleted_by_admin_at');
  }

  public function scopeFullyDeleted(Builder $query): Builder
  {
    return $query->whereNotNull('deleted_by_user_at')
      ->whereNotNull('deleted_by_admin_at');
  }

  // ─── Soft Delete Methods ──────────────────────────────────
  public function deleteByUser()
  {
    return $this->update(['deleted_by_user_at' => now()]);
  }

  public function deleteByAdmin()
  {
    return $this->update(['deleted_by_admin_at' => now()]);
  }

  public function restoreByUser()
  {
    return $this->update(['deleted_by_user_at' => null]);
  }

  public function restoreByAdmin()
  {
    return $this->update(['deleted_by_admin_at' => null]);
  }

  public function isDeletedByUser()
  {
    return $this->deleted_by_user_at !== null;
  }

  public function isDeletedByAdmin()
  {
    return $this->deleted_by_admin_at !== null;
  }
}
