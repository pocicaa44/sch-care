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

  // // ─── Scopes ──────────────────────────────────────────────
  // public function scopeVisibleToUser(Builder $query): Builder
  // {
  //   return $query->whereNull('deleted_by_user_at');
  // }

  // public function scopeVisibleToAdmin(Builder $query): Builder
  // {
  //   return $query->whereNull('deleted_by_admin_at');
  // }

  // public function scopeFullyDeleted(Builder $query): Builder
  // {
  //   return $query->whereNotNull('deleted_by_user_at')
  //     ->whereNotNull('deleted_by_admin_at');
  // }

  // // ─── Soft Delete ─────────────────────────────────────────
  // public function deleteByUser(): void
  // {
  //   $this->update(['deleted_by_user_at' => now()]);
  // }

  // public function deleteByAdmin(): void
  // {
  //   $this->update(['deleted_by_admin_at' => now()]);
  // }

  // // ─── Restore ─────────────────────────────────────────────
  // public function restoreByAdmin(): void
  // {
  //   $this->update(['deleted_by_admin_at' => null]);
  // }

  // // ─── Helpers ─────────────────────────────────────────────
  // public function isDeletedByUser(): bool
  // {
  //   return !is_null($this->deleted_by_user_at);
  // }

  // public function isDeletedByAdmin(): bool
  // {
  //   return !is_null($this->deleted_by_admin_at);
  // }

  // public function isFullyDeleted(): bool
  // {
  //   return $this->isDeletedByUser() && $this->isDeletedByAdmin();
  // }
}
